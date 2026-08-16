<?php

namespace App\Services\AI\Providers;

use App\DTOs\AI\GenerateRequest;
use App\DTOs\AI\GenerateResponse;
use App\DTOs\AI\HealthCheckResult;
use App\Services\AI\Contracts\AIProviderInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class OllamaProvider implements AIProviderInterface
{
    private Client $client;
    private string $baseUrl;
    private int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('ai.ollama.url', 'http://127.0.0.1:11434'), '/');
        $this->timeout = (int) config('ai.ollama.timeout', 120);

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout'  => $this->timeout,
            'headers'  => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ],
        ]);
    }

    public function generate(GenerateRequest $request): GenerateResponse
    {
        $startTime = microtime(true);

        // Build the prompt with structured output requirement
        $userMessage = $this->buildUserMessage($request);

        $payload = [
            'model'  => $request->modelId,
            'stream' => false,
            'options' => [
                'temperature' => $request->temperature,
            ],
            'messages' => [
                [
                    'role'    => 'system',
                    'content' => $request->systemPrompt,
                ],
                [
                    'role'    => 'user',
                    'content' => $userMessage,
                ],
            ],
        ];

        // Request structured JSON output if supported
        if ($request->structuredOutput) {
            $payload['format'] = 'json';
        }

        if ($request->maxOutputTokens) {
            $payload['options']['num_predict'] = $request->maxOutputTokens;
        }

        try {
            $response = $this->client->post('/api/chat', [
                'json' => $payload,
            ]);

            $durationMs = (int) ((microtime(true) - $startTime) * 1000);
            $body = json_decode($response->getBody()->getContents(), true);

            $rawContent = $body['message']['content'] ?? '';

            // Parse structured response
            $parsed = $this->parseStructuredResponse($rawContent, $request->segments);

            return new GenerateResponse(
                success:        $parsed['success'],
                segments:       $parsed['segments'],
                rawResponse:    $rawContent,
                requestTokens:  $body['prompt_eval_count'] ?? null,
                responseTokens: $body['eval_count'] ?? null,
                durationMs:     $durationMs,
                error:          $parsed['error'] ?? null,
            );

        } catch (ConnectException $e) {
            Log::error('Ollama connection failed', ['error' => $e->getMessage()]);
            return GenerateResponse::failure('Cannot connect to Ollama. Is it running at ' . $this->baseUrl . '?');
        } catch (RequestException $e) {
            Log::error('Ollama request failed', [
                'status' => $e->getResponse()?->getStatusCode(),
                'error'  => $e->getMessage(),
            ]);
            return GenerateResponse::failure('Ollama returned an error: ' . $e->getMessage());
        } catch (\Throwable $e) {
            Log::error('Ollama unexpected error', ['error' => $e->getMessage()]);
            return GenerateResponse::failure('Unexpected error: ' . $e->getMessage());
        }
    }

    public function healthCheck(): HealthCheckResult
    {
        $startTime = microtime(true);

        try {
            $response = $this->client->get('/api/tags', [
                'timeout' => 5,
            ]);

            $durationMs = (int) ((microtime(true) - $startTime) * 1000);
            $body = json_decode($response->getBody()->getContents(), true);

            $models = array_map(
                fn($m) => $m['name'],
                $body['models'] ?? []
            );

            return new HealthCheckResult(
                online:          true,
                responseTimeMs:  $durationMs,
                availableModels: $models,
            );

        } catch (ConnectException $e) {
            return HealthCheckResult::offline('Cannot connect to Ollama at ' . $this->baseUrl);
        } catch (\Throwable $e) {
            return HealthCheckResult::offline($e->getMessage());
        }
    }

    public function availableModels(): array
    {
        try {
            $response = $this->client->get('/api/tags');
            $body = json_decode($response->getBody()->getContents(), true);
            return $body['models'] ?? [];
        } catch (\Throwable) {
            return [];
        }
    }

    public function supportsStructuredOutput(): bool
    {
        return true;
    }

    public function getDriver(): string
    {
        return 'ollama';
    }

    private function buildUserMessage(GenerateRequest $request): string
    {
        $segmentsJson = json_encode($request->segments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $protectedNote = '';
        if (!empty($request->protectedValues)) {
            $protectedList = implode(', ', array_map(fn($v) => '"' . $v . '"', $request->protectedValues));
            $protectedNote = "\n\nCRITICAL: The following values MUST be preserved EXACTLY as-is in your rewrite: {$protectedList}";
        }

        return <<<PROMPT
{$request->instructions}{$protectedNote}

Rewrite the following content segments. Return ONLY valid JSON with the exact schema below:

{
  "segments": [
    {
      "id": "segment_001",
      "original": "original text here",
      "rewritten": "rewritten text here"
    }
  ]
}

Content to rewrite:
{$segmentsJson}
PROMPT;
    }

    private function parseStructuredResponse(string $rawContent, array $segments): array
    {
        // Strip any markdown code fences if present
        $cleaned = preg_replace('/^```(?:json)?\s*/m', '', $rawContent);
        $cleaned = preg_replace('/```\s*$/m', '', $cleaned);
        $cleaned = trim($cleaned);

        $decoded = json_decode($cleaned, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success'  => false,
                'segments' => [],
                'error'    => 'AI response is not valid JSON: ' . json_last_error_msg(),
            ];
        }

        if (!isset($decoded['segments']) || !is_array($decoded['segments'])) {
            return [
                'success'  => false,
                'segments' => [],
                'error'    => 'AI response missing "segments" array.',
            ];
        }

        return [
            'success'  => true,
            'segments' => $decoded['segments'],
        ];
    }
}

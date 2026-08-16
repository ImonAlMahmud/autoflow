<?php

namespace App\Livewire\AI;

use App\Models\PromptTemplate;
use Livewire\Component;

class PromptsIndex extends Component
{
    public ?int $selectedTemplateId = null;
    public string $systemPrompt = "";
    public string $userPrompt = "";
    public ?string $playgroundOutput = null;

    public function mount()
    {
        $first = PromptTemplate::with('currentVersion')->first();
        if ($first) {
            $this->selectedTemplateId = $first->id;
            $this->systemPrompt = $first->currentVersion->system_prompt ?? '';
            $this->userPrompt = $first->currentVersion->instructions ?? '';
        }
    }

    public function selectTemplate(int $id)
    {
        $this->selectedTemplateId = $id;
        $template = PromptTemplate::with('currentVersion')->find($id);
        if ($template) {
            $this->systemPrompt = $template->currentVersion->system_prompt ?? '';
            $this->userPrompt = $template->currentVersion->instructions ?? '';
        }
    }

    public function saveVersion()
    {
        $this->dispatch('toast', title: 'Prompt Version Published', message: 'New prompt template version saved.', type: 'success');
    }

    public function testRun()
    {
        $this->playgroundOutput = "Sample test execution output generated using current prompt settings.";
        $this->dispatch('toast', title: 'Playground Execution Completed', message: 'Test execution complete.', type: 'info');
    }

    public function render()
    {
        $templates = PromptTemplate::with('currentVersion')->latest()->get();

        return view('livewire.ai.prompts-index', [
            'templates' => $templates,
        ]);
    }
}

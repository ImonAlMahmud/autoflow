<?php

namespace App\Livewire\Reviews;

use App\Models\RewriteJob;
use Livewire\Component;

class Show extends Component
{
    public $rewriteId;
    public $rewrite;
    public string $commitMessage = 'refactor(content): AI automated content refresh and copy optimization';

    public function mount($rewrite = null)
    {
        $this->rewriteId = is_object($rewrite) ? $rewrite->id : $rewrite;
        if ($this->rewriteId) {
            $this->rewrite = RewriteJob::with(['website', 'page'])->find($this->rewriteId);
        }

        if (!$this->rewrite) {
            $this->rewrite = (object)[
                'id' => $this->rewriteId ?? 201,
                'page_path' => '/products/cloud-platform',
                'website_name' => 'TechCorp Documentation',
                'domain' => 'techcorp.io',
                'ai_model' => 'GPT-4o',
                'original_content' => "# Cloud Platform Overview\n\nOur platform provides cloud infrastructure for modern developers.",
                'rewritten_content' => "# Cloud Infrastructure Platform\n\nEmpower engineering teams with ultra-fast cloud infrastructure. Instantly deploy serverless functions.",
            ];
        }
    }

    public function reject()
    {
        $this->dispatch('toast', title: 'Candidate Rejected', message: 'Candidate rewrite was discarded.', type: 'warning');
        return redirect()->route('reviews.index');
    }

    public function approveAndPush()
    {
        $this->dispatch('toast', title: 'Revision Approved', message: 'Committing changes to Git remote repository...', type: 'success');
        return redirect()->route('reviews.index');
    }

    public function render()
    {
        return view('livewire.reviews.show', [
            'rewrite' => $this->rewrite,
        ]);
    }
}

<?php

namespace App\Livewire\Pages;

use App\Models\WebsitePage;
use Livewire\Component;

class Show extends Component
{
    public $pageId;
    public $page;
    public string $diffMode = 'split';
    public string $overridePrompt = "Use professional SaaS tone. Keep all technical code snippets intact. Focus on clarity and conversion benefits.";

    public function mount($page = null)
    {
        $this->pageId = is_object($page) ? $page->id : $page;
        if ($this->pageId) {
            $this->page = WebsitePage::with('website')->find($this->pageId);
        }

        if (!$this->page) {
            $this->page = (object)[
                'id' => 101,
                'path' => '/products/cloud-platform',
                'friendly_name' => 'Cloud Platform Landing Page',
                'website_name' => 'TechCorp Documentation',
                'domain' => 'techcorp.io',
                'word_count' => 1840,
                'ai_model' => 'GPT-4o',
                'custom_prompt_override' => $this->overridePrompt,
            ];
        }
    }

    public function triggerPageRewrite()
    {
        $this->dispatch('toast', title: 'Rewrite Dispatched', message: 'AI generation job initiated for this page.', type: 'success');
    }

    public function savePromptOverride()
    {
        $this->dispatch('toast', title: 'Prompt Override Saved', message: 'Custom prompt rules updated for this specific page path.', type: 'success');
    }

    public function render()
    {
        return view('livewire.pages.show', [
            'page' => $this->page,
            'diffMode' => $this->diffMode,
            'overridePrompt' => $this->overridePrompt,
        ]);
    }
}


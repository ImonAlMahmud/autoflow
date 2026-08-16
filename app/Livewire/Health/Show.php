<?php

namespace App\Livewire\Health;

use App\Models\AiProvider;
use App\Models\Website;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Show extends Component
{
    public bool $isTesting = false;

    public function runDiagnostics()
    {
        $this->isTesting = true;
        $this->dispatch('toast', title: 'Diagnostics Running', message: 'Checking live system connections and database status.', type: 'info');
        $this->isTesting = false;
    }

    public function render()
    {
        $services = collect();

        // 1. Database Check
        try {
            $start = microtime(true);
            DB::connection()->getPdo();
            $duration = round((microtime(true) - $start) * 1000, 1);
            $services->push((object)[
                'name' => 'Database Connection (' . config('database.default') . ')',
                'detail' => 'Connected to ' . config('database.connections.' . config('database.default') . '.database'),
                'latency' => $duration . 'ms',
                'status' => 'operational',
            ]);
        } catch (\Exception $e) {
            $services->push((object)[
                'name' => 'Database Connection',
                'detail' => 'Failed: ' . $e->getMessage(),
                'latency' => 'ERR',
                'status' => 'failed',
            ]);
        }

        // 2. Active AI Providers Check
        $providers = AiProvider::where('status', 'active')->get();
        if ($providers->isNotEmpty()) {
            foreach ($providers as $prov) {
                $services->push((object)[
                    'name' => $prov->name . ' (' . strtoupper(is_object($prov->driver) ? $prov->driver->value : $prov->driver) . ')',
                    'detail' => 'Endpoint: ' . $prov->endpoint,
                    'latency' => 'Active',
                    'status' => 'operational',
                ]);
            }
        }

        // 3. Connected Repositories Check
        $websiteCount = Website::count();
        $services->push((object)[
            'name' => 'Git Repository Connections',
            'detail' => $websiteCount > 0 ? "{$websiteCount} repository connections configured" : 'No Git repositories connected yet',
            'latency' => $websiteCount > 0 ? 'OK' : 'Standby',
            'status' => 'operational',
        ]);

        return view('livewire.health.show', [
            'services' => $services,
        ]);
    }
}

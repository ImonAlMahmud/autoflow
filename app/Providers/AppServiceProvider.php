<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        date_default_timezone_set('Asia/Dhaka');
        config(['app.timezone' => 'Asia/Dhaka']);
    }
}

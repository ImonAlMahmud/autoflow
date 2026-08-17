<?php

use Illuminate\Support\Facades\Route;

// Public SaaS Marketing Routes (Ideomet Technologies)
Route::get('/', function () {
    return view('marketing.home');
})->name('home');

Route::get('/about', function () {
    return view('marketing.about');
})->name('about');

Route::get('/how-it-works', function () {
    return view('marketing.how-it-works');
})->name('how-it-works');

Route::get('/pricing', function () {
    return view('marketing.pricing');
})->name('pricing');

// Web-based Cron Trigger for Shared Hosting (Runs schedule without SSH)
Route::get('/cron/run', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('schedule:run');
        $output = \Illuminate\Support\Facades\Artisan::output();
        return response()->json([
            'status' => 'success',
            'timestamp' => now()->toIso8601String(),
            'message' => 'Schedule executed successfully.',
            'output' => $output,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
})->name('cron.run');

Route::get('/contact', function () {
    return view('marketing.contact');
})->name('contact');

// Public SaaS User Registration Route
Route::middleware('guest')->group(function () {
    Route::get('/register', \App\Livewire\Auth\Register::class)->name('register');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', \App\Livewire\Dashboard\Overview::class)->name('dashboard');

    Route::prefix('websites')->name('websites.')->group(function () {
        Route::get('/', \App\Livewire\Websites\Index::class)->name('index');
        Route::get('/create', \App\Livewire\Websites\Create::class)->name('create');
        Route::get('/{website}', \App\Livewire\Websites\Show::class)->name('show');
        Route::get('/{website}/edit', \App\Livewire\Websites\Edit::class)->name('edit');
    });

    Route::prefix('pages')->name('pages.')->group(function () {
        Route::get('/', \App\Livewire\Pages\Index::class)->name('index');
        Route::get('/{page}', \App\Livewire\Pages\Show::class)->name('show');
    });

    Route::prefix('jobs')->name('jobs.')->group(function () {
        Route::get('/', \App\Livewire\Jobs\Index::class)->name('index');
        Route::get('/{job}', \App\Livewire\Jobs\Show::class)->name('show');
    });

    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', \App\Livewire\Reviews\Index::class)->name('index');
        Route::get('/{rewrite}', \App\Livewire\Reviews\Show::class)->name('show');
    });

    Route::prefix('ai')->name('ai.')->group(function () {
        Route::get('/models', \App\Livewire\AI\ModelsIndex::class)->name('models');
        Route::get('/prompts', \App\Livewire\AI\PromptsIndex::class)->name('prompts');
    });

    Route::prefix('git')->name('git.')->group(function () {
        Route::get('/activity', \App\Livewire\Git\Activity::class)->name('activity');
        Route::get('/index', \App\Livewire\Git\Activity::class)->name('index');
    });

    Route::prefix('logs')->name('logs.')->group(function () {
        Route::get('/', \App\Livewire\Logs\Index::class)->name('index');
    });

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', \App\Livewire\Settings\Index::class)->name('index');
    });

    Route::get('/how-to-use', \App\Livewire\User\Tutorial::class)->name('how-to-use');
    Route::get('/subscription', \App\Livewire\Subscription\Index::class)->name('subscription');
    Route::get('/system-health', \App\Livewire\Health\Show::class)->name('system-health');
    Route::get('/system/deployment', function () {
        return view('deployment-guide');
    })->name('system.deployment');
    Route::get('/settings', \App\Livewire\Settings\Index::class)->name('settings');
    Route::get('/profile', \App\Livewire\Profile\Show::class)->name('profile');

    // Super Admin Routes (God of application)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', \App\Livewire\Admin\UsersIndex::class)->name('users');
        Route::get('/websites', \App\Livewire\Admin\AllWebsites::class)->name('websites');
    });
});

require __DIR__.'/auth.php';

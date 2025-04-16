<?php

use App\Http\Middleware\CheckForOffline;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Route::get('/offline', function () {
//     return 'Show ...';
// })->name('offline');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Settings
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // Products
    Route::redirect('products', 'products/grid');

    Volt::route('products/grid', 'products.grid')->name('products.grid');

    // Task Managers
    Route::redirect('taskmanagers', 'taskmanagers/index');

    Volt::route('taskmanagers/index', 'taskmanagers.index')->name('taskmanagers.index');
    Volt::route('taskmanagers/model', 'taskmanagers.model')->name('taskmanagers.model');
});


require __DIR__.'/auth.php';

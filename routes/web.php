<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Admin\Categories\CategoryList;
use App\Livewire\Admin\Subcategories\SubcategoryList;
use App\Livewire\Admin\Products\ProductList;
use Illuminate\Support\Facades\Route;

use App\Livewire\Admin\Auth\Login as AdminLogin;
use App\Livewire\Admin\Dashboard as AdminDashboard;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});


Route::prefix('admin')->group(function () {
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('admin.dashboard');
        Route::get('/categories', CategoryList::class)->name('admin.categories');
        Route::get('/subcategories', SubcategoryList::class)->name('admin.subcategories');
        Route::get('/products', ProductList::class)->name('admin.products');
        
    });

    Route::get('/', function () {
        return redirect()->route('admin.login');
    })->name('admin.home');

    Route::get('/login', AdminLogin::class)->name('admin.login');
});

require __DIR__.'/auth.php';

<?php

namespace App\Providers;

use App\Livewire\Admin\Categories\CategoryList;
use App\Livewire\Admin\Subcategories\SubcategoryList;
use App\Livewire\Admin\Products\ProductList;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Livewire::component('admin.categories.category-list', CategoryList::class);
        Livewire::component('admin.subcategories.subcategory-list', SubcategoryList::class);
        Livewire::component('admin.products.product-list', ProductList::class);
    }
}

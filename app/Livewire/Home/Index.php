<?php

namespace App\Livewire\Home;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $category = '';
    public $subcategory = '';
    public $minPrice;
    public $maxPrice;
    public $search = '';
    public $sort = 'asc';

    protected $queryString = [
        'category' => ['except' => ''],
        'subcategory' => ['except' => ''],
        'minPrice' => ['except' => ''],
        'maxPrice' => ['except' => ''],
        'search' => ['except' => ''],
        'sort' => ['except' => 'asc'],
    ];

    public function render()
    {
        $categories = Category::with('subcategories')->active()->get();

        $products = Product::with('subcategory.category')
            ->where('is_active', true)
            ->when($this->search, fn($q) =>
                $q->where(function($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('description', 'like', "%{$this->search}%");
                }))
            ->when($this->category, fn($q) =>
                $q->whereHas('subcategory.category', fn($q2) =>
                    $q2->where('id', $this->category)))
            ->when($this->subcategory, fn($q) =>
                $q->where('subcategory_id', $this->subcategory))
            ->when($this->minPrice, fn($q) =>
                $q->where('price', '>=', $this->minPrice))
            ->when($this->maxPrice, fn($q) =>
                $q->where('price', '<=', $this->maxPrice))
            ->orderBy('price', $this->sort)
            ->paginate(9);

        return view('livewire.home.index', compact('categories', 'products'))->layout('layouts.web');;
    }

    public function updatedCategory()
    {
        $this->subcategory = '';
        $this->resetPage();
    }

    public function toggleSort()
    {
        $this->sort = $this->sort === 'asc' ? 'desc' : 'asc';
    }
}

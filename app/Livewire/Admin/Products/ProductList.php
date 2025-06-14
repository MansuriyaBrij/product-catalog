<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use App\Models\Subcategory;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
#[Layout('layouts.admin')]

class ProductList extends Component
{
    use WithPagination, WithFileUploads;

    #[Rule('required|min:2|max:255|unique:products,name')]
    public $name = '';

    #[Rule('nullable|string|max:1000')]
    public $description = '';

    #[Rule('required|numeric|min:0|max:999999.99')]
    public $price = '';

    #[Rule('required|exists:subcategories,id')]
    public $subcategory_id = '';

    #[Rule('image|max:1024')] // 1MB Max
    public $image;

    #[Rule('boolean')]
    public $is_active = true;

    public $editingProductId = null;
    public $isEditing = false;
    public $selectedCategory = null;

    protected $listeners = ['refreshProducts' => '$refresh'];

    public function rules()
    {
        $imageRule = $this->isEditing ? 'nullable|image|max:2048' : 'required|image|max:2048';
        
        return [
            'name' => 'required|min:2|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0|max:999999.99',
            'subcategory_id' => 'required|exists:subcategories,id',
            'image' => $imageRule,
            'is_active' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The product name is required.',
            'name.unique' => 'This product name already exists.',
            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a number.',
            'price.min' => 'The price cannot be negative.',
            'price.max' => 'The price cannot exceed 999,999.99.',
            'subcategory_id.required' => 'Please select a subcategory.',
            'image.image' => 'The file must be an image.',
            'image.max' => 'The image must not exceed 2MB.',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function create()
    {
        $this->validate();

        try {
            $imagePath = $this->image->store('products', 'public');
            
            Product::create([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'subcategory_id' => $this->subcategory_id,
                'image_path' => $imagePath,
                'is_active' => $this->is_active,
            ]);

            $this->reset(['name', 'description', 'price', 'subcategory_id', 'image', 'is_active']);
            session()->flash('message', 'Product created successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error creating product. Please try again.');
        }
    }

    public function edit($productId)
    {
        $this->editingProductId = $productId;
        $product = Product::find($productId);
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->subcategory_id = $product->subcategory_id;
        $this->is_active = $product->is_active;
        $this->selectedCategory = $product->subcategory->category_id;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        try {
            $product = Product::find($this->editingProductId);
            
            $data = [
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'subcategory_id' => $this->subcategory_id,
                'is_active' => $this->is_active,
            ];

            if ($this->image) {
                if ($product->image_path) {
                    Storage::disk('public')->delete($product->image_path);
                }
                $data['image_path'] = $this->image->store('products', 'public');
            }

            $product->update($data);
            
            $this->reset(['name', 'description', 'price', 'subcategory_id', 'image', 'is_active', 'editingProductId', 'isEditing', 'selectedCategory']);
            session()->flash('message', 'Product updated successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error updating product. Please try again.');
        }
    }

    public function delete($productId)
    {
        try {
            $product = Product::find($productId);
            
            if ($product->image_path) {
                Storage::disk('public')->delete(str_replace('\\', '/', $product->image_path));
            }
            
            $product->delete();
            session()->flash('message', 'Product deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting product: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $subcategories = $this->selectedCategory
            ? Subcategory::where('category_id', $this->selectedCategory)->get()
            : collect();

        return view('livewire.admin.products.product-list', [
            'products' => Product::with(['subcategory.category'])->orderBy('name')->paginate(10),
            'categories' => \App\Models\Category::orderBy('name')->get(),
            'subcategories' => $subcategories,
        ]);
    }

    public function updatedSelectedCategory($value)
    {
        $this->subcategory_id = null;
    }
}

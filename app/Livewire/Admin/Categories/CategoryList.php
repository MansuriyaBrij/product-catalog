<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('layouts.admin')]
class CategoryList extends Component
{
    use WithPagination;

    #[Rule('required|min:2|max:255|unique:categories,name')]
    public $name = '';

    #[Rule('boolean')]
    public $is_active = true;

    public $editingCategoryId = null;
    public $isEditing = false;

    public function messages()
    {
        return [
            'name.required' => 'The category name is required.',
            'name.min' => 'The category name must be at least 2 characters.',
            'name.max' => 'The category name cannot exceed 255 characters.',
            'name.unique' => 'This category name already exists.',
        ];
    }

    public function rules()
    {
        return [
            'name' => 'required|min:2|max:255',
            'is_active' => 'boolean',
        ];
    }

    public function create()
    {
        $this->validate();

        Category::create([
            'name' => $this->name,
            'is_active' => $this->is_active,
        ]);

        $this->reset(['name', 'is_active']);
        session()->flash('message', 'Category created successfully.');
    }

    public function edit($categoryId)
    {
        $this->editingCategoryId = $categoryId;
        $category = Category::find($categoryId);
        $this->name = $category->name;
        $this->is_active = $category->is_active;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        $category = Category::find($this->editingCategoryId);
        $category->update([
            'name' => $this->name,
            'is_active' => $this->is_active,
        ]);

        $this->reset(['name', 'is_active', 'editingCategoryId', 'isEditing']);
        session()->flash('message', 'Category updated successfully.');
    }

    public function delete($categoryId)
    {
        Category::find($categoryId)->delete();
        session()->flash('message', 'Category deleted successfully.');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.admin.categories.category-list', [
            'categories' => Category::orderBy('name')->paginate(10),
        ]);
    }
}

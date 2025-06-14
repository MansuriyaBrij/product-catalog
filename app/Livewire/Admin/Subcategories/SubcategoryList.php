<?php

namespace App\Livewire\Admin\Subcategories;

use App\Models\Category;
use App\Models\Subcategory;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('layouts.admin')]
class SubcategoryList extends Component
{
    use WithPagination;

    #[Rule('required|min:2|max:255|unique:subcategories,name')]
    public $name = '';

    #[Rule('required|exists:categories,id')]
    public $category_id = '';

    #[Rule('boolean')]
    public $is_active = true;

    public $editingSubcategoryId = null;
    public $isEditing = false;

    public function messages()
    {
        return [
            'name.required' => 'The subcategory name is required.',
            'name.min' => 'The subcategory name must be at least 2 characters.',
            'name.unique' => 'This subcategory name already exists.',
            'category_id.required' => 'Please select a parent category.',
            'category_id.exists' => 'The selected category does not exist.',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function rules()
    {
        return [
            'name' => 'required|min:2|max:255',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
        ];
    }

    public function create()
    {
        $this->validate();

        Subcategory::create([
            'name' => $this->name,
            'category_id' => $this->category_id,
            'is_active' => $this->is_active,
        ]);

        $this->reset(['name', 'category_id', 'is_active']);
        session()->flash('message', 'Subcategory created successfully.');
    }

    public function edit($subcategoryId)
    {
        $this->editingSubcategoryId = $subcategoryId;
        $subcategory = Subcategory::find($subcategoryId);
        $this->name = $subcategory->name;
        $this->category_id = $subcategory->category_id;
        $this->is_active = $subcategory->is_active;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        $subcategory = Subcategory::find($this->editingSubcategoryId);
        $subcategory->update([
            'name' => $this->name,
            'category_id' => $this->category_id,
            'is_active' => $this->is_active,
        ]);

        $this->reset(['name', 'category_id', 'is_active', 'editingSubcategoryId', 'isEditing']);
        session()->flash('message', 'Subcategory updated successfully.');
    }

    public function delete($subcategoryId)
    {
        Subcategory::find($subcategoryId)->delete();
        session()->flash('message', 'Subcategory deleted successfully.');
    }

    public function render()
    {
        return view('livewire.admin.subcategories.subcategory-list', [
            'subcategories' => Subcategory::with('category')->orderBy('name')->paginate(10),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }
}

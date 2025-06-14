<div class="bg-gray-100 dark:bg-zinc-800 min-h-screen">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <div class="bg-white dark:bg-zinc-900 shadow-sm rounded-lg p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4 dark:text-white">{{ $isEditing ? 'Edit Product' : 'Create New Product' }}</h2>
            <form wire:submit.prevent="{{ $isEditing ? 'update' : 'create' }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Category</label>
                        <select wire:model.live="selectedCategory"
                            class="w-full mt-1 px-3 py-2 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Subcategory</label>
                        <select wire:model.live="subcategory_id"
                            class="w-full mt-1 px-3 py-2 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select Subcategory</option>
                            @foreach($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                            @endforeach
                        </select>
                        @error('subcategory_id') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
                        <input type="text" wire:model.live="name"
                            class="w-full mt-1 px-3 py-2 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('name') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Price</label>
                        <input type="number" step="0.01" wire:model.live="price"
                            class="w-full mt-1 px-3 py-2 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('price') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Description</label>
                    <textarea wire:model.live="description" rows="3"
                        class="w-full mt-1 px-3 py-2 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    @error('description') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Image</label>
                    <input type="file" wire:model="image" accept="image/*"
                        class="w-full mt-1 text-sm text-gray-500 dark:text-gray-400
                        file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                        file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700
                        hover:file:bg-indigo-100 dark:file:bg-zinc-700 dark:file:text-zinc-100">
                    @error('image') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="is_active" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-700 dark:text-gray-300">Active</span>
                    </label>
                </div>

                <div class="flex justify-end space-x-3">
                    @if($isEditing)
                        <button type="button" wire:click="$set('isEditing', false)" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                            Cancel
                        </button>
                    @endif
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        {{ $isEditing ? 'Update' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-zinc-900 shadow-sm rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($products as $product)
                            <tr class="dark:text-gray-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="h-10 w-10 rounded-full">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($product->price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $product->subcategory->category->name }} / {{ $product->subcategory->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button wire:click="edit({{ $product->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                    <button wire:click="delete({{ $product->id }})" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 dark:bg-zinc-900">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
                       

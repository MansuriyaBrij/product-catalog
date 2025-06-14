<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Filters Sidebar -->
        <div class="w-full md:w-64 space-y-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" wire:model.live="search" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Category</label>
                <select wire:model.live="selectedCategory" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Subcategory Filter -->
            @if($selectedCategory)
            <div>
                <label class="block text-sm font-medium text-gray-700">Subcategory</label>
                <select wire:model.live="selectedSubcategory" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Subcategories</option>
                    @foreach($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <!-- Price Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Price Range</label>
                <div class="mt-1 grid grid-cols-2 gap-2">
                    <input type="number" wire:model.live="minPrice" placeholder="Min" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <input type="number" wire:model.live="maxPrice" placeholder="Max" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <!-- Sort -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Sort By</label>
                <select wire:model.live="sortField" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="created_at">Date</option>
                    <option value="price">Price</option>
                    <option value="name">Name</option>
                </select>
                <button wire:click="$set('sortDirection', '{{ $sortDirection === 'asc' ? 'desc' : 'asc' }}')" class="mt-1 inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-sm">
                    {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                </button>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="flex-1">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        @if($product->image_path)
                            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500">No image</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-500">{{ Str::limit($product->description, 100) }}</p>
                            <div class="mt-2 flex items-center justify-between">
                                <span class="text-lg font-bold text-indigo-600">${{ number_format($product->price, 2) }}</span>
                                <span class="text-sm text-gray-500">{{ $product->subcategory->name }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500">
                        No products found matching your criteria.
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

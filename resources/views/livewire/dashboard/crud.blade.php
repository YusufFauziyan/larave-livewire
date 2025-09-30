<?php

use Livewire\Volt\Component;
use App\Models\Product;
use Livewire\WithPagination;


new class extends Component {
    use WithPagination;

    // state for open modal
    public bool $isProductModalOpen = false;
    public ?int $editingId = null;

    // state for search, sorting, and pagination
    public string $search = '';
    public string $sortField = 'id';
    public string $sortDirection = 'asc';
    public int $perPage = 10;

    // State for form inputs
    public string $name = '';
    public string $description = '';
    public ?int $price = null;
    public ?int $stock = null;

    // query string for state persistence
    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'id'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
    ];

    // validation rules
    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
        ];
    }

    public function openCreateModal()
    {
        $this->reset(['editingId', 'name', 'description', 'price', 'stock']);
        $this->isProductModalOpen = true;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    // put the logic to open edit modal and load product data
    public function openEditModal($id)
    {
        $product = Product::findOrFail($id);

        $this->editingId = $product->id;
        $this->name = $product->name;
        $this->description = $product->description ?? '';
        $this->price = $product->price;
        $this->stock = $product->stock;

        $this->isProductModalOpen = true;
    }

    // create or edit product
    public function saveProduct()
    {
        $validated = $this->validate();

        if ($this->editingId) {
            // update
            $product = Product::findOrFail($this->editingId);
            $product->update([
                'name' => $validated['name'],
                'description' => $this->description,
                'price' => $validated['price'],
                'stock' => $validated['stock'],
            ]);
            $message = 'Product updated successfully!';
        } else {
            // create
            Product::create([
                'name' => $validated['name'],
                'description' => $this->description,
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'user_id' => auth()->id(),
            ]);
            $message = 'Product added successfully!';
        }

        $this->reset(['editingId', 'name', 'description', 'price', 'stock']);
        $this->isProductModalOpen = false;
        $this->dispatch('notify', message: $message, type: 'success');
        $this->resetPage();
    }

    // delete product
    public function deleteProduct($id)
    {
        Product::find($id)->delete();

        // refresh data
        $this->resetPage();

        // Dispatch event untuk menutup semua modal
        $this->dispatch('product-deleted');

        // Tambahkan notifikasi sukses
        $this->dispatch('notify', message: 'Product deleted successfully!', type: 'success');
    }

    public function with(): array
    {
        $query = Product::query()
            ->select('products.*')
            ->with('user')
            ->when($this->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('products.name', 'like', "%{$search}%")
                        ->orWhere('products.description', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                });
            });

        // ✅ handle sorting
        if ($this->sortField === 'user.name') {
            $query->join('users', 'users.id', '=', 'products.user_id')
                ->orderBy('users.name', $this->sortDirection);
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $products = $query->paginate($this->perPage);

        return [
            'products' => $products,
        ];
    }

    public function stockHtml($item)
    {
        $color = $item->stock > 10 ? 'green' : ($item->stock > 5 ? 'yellow' : 'red');

        return '<div class="flex">
            <div class="px-3 py-1 rounded-full text-center ' . ($item->stock > 10 ? 'bg-green-50/10' : ($item->stock > 5 ? 'bg-yellow-50/10' : 'bg-red-50/10')) . '">
            <p class="text-' . $color . '-500 text-xs font-bold">
                ' . $item->stock . '
                </p>
            </div>
            </div>
            ';
    }

};
?>

<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header Card -->
        <x-card>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div class="w-full">
                    <div class="flex items-center justify-between gap-4 w-full">
                        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Products Management</h1>

                        <flux:button variant="primary" wire:click="openCreateModal">
                            Add Product
                        </flux:button>
                    </div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">Manage and view all your products</p>
                </div>
            </div>

            <!-- Search Bar -->
            <div>
                <div class="relative">
                    <flux:input wire:model.live.debounce.300ms="search" placeholder="Search products..."
                        icon="magnifying-glass" clearable />

                    @if($search)
                        <button wire:click="clearSearch"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>
                @if($search)
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-2">
                        Searching for: <span class="font-semibold text-zinc-900 dark:text-white">"{{ $search }}"</span>
                    </p>
                @endif
            </div>
        </x-card>

        <x-table :items="$products" :columns="[
        'id' => '#',
        'name' => 'Name',
        'description' => 'Description',
        'price' => 'Price',
        'stock' => 'Stock',
        'user.name' => 'User',
        'actions' => 'Actions',
    ]" :columnClasses="[
        'description' => 'max-w-[250px] truncate',
    ]" :customColumns="[
        'price' => fn($item) => 'Rp. ' . number_format($item->price, 0, ',', '.'),
        'stock' => fn($item) => $this->stockHtml($item),
        'actions' => fn($item) => view('components.table-actions', [
            'item' => $item,
            'editAction' => 'openEditModal',
            'deleteAction' => 'deleteProduct',
        ])->render(),

    ]" :sortableColumns="['id', 'name', 'price', 'stock', 'user.name']" :sortField="$sortField" :sortDirection="$sortDirection" :perPage="$perPage"
            :search="$search" />
    </div>

    <!-- Modal Add Product -->
    <flux:modal wire:model="isProductModalOpen" class="md:w-96">
        <form wire:submit.prevent="saveProduct" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $editingId ? 'Edit Product' : 'Add Product' }}</flux:heading>
                <flux:text class="mt-2">Fill in the details to {{ $editingId ? 'edit' : 'add' }} a product.</flux:text>
            </div>

            <flux:input label="Name" placeholder="Product name" wire:model="name" required />
            @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

            <flux:textarea label="Description" placeholder="Brief description about the product" rows="3"
                wire:model="description" />

            <flux:input type="number" placeholder="Input price" label="Price" wire:model="price" required />
            @error('price') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

            <flux:input label="Stock" placeholder="Product stock" type="number" wire:model="stock" required />
            @error('stock') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">{{ $editingId ? 'Update Product' : 'Add Product' }}
                </flux:button>
            </div>
        </form>
    </flux:modal>

</div>
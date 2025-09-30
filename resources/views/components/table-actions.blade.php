@props([
    'item',
    'editAction' => null,
    'deleteAction' => null,
])

<div class="px-6 py-4 whitespace-nowrap text-right" x-data="{ open: false }">
    <!-- Tombol trigger -->
    @if ($editAction || $deleteAction)
        <button @click="open = !open"
            class="p-1 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-zinc-300 dark:focus:ring-zinc-600"
            aria-label="More options">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="currentColor"
                viewBox="0 0 20 20">
                <path
                    d="M9 15.25a1.25 1.25 0 1 1 2.5 0a1.25 1.25 0 0 1-2.5 0m0-5a1.25 1.25 0 1 1 2.5 0a1.25 1.25 0 0 1-2.5 0m0-5a1.249 1.249 0 1 1 2.5 0a1.25 1.25 0 1 1-2.5 0" />
            </svg>
        </button>
    @endif

    <!-- Popover menu -->
    <div x-show="open" 
        @click.outside="open = false" 
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" 
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" 
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute -translate-x-1/2 right-7 mt-2 w-40 bg-white dark:bg-zinc-800 rounded-xl shadow-xl border border-gray-200 dark:border-zinc-700 z-50 overflow-hidden"
        style="display: none;">
        <div class="py-1">
            @if($editAction)
                <flux:button 
                    wire:click="{{ $editAction }}({{ $item->id }})"
                    variant="ghost" 
                    icon="pencil"
                    class="w-full justify-start px-4 py-2.5 text-sm hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors"
                    @click="open = false">
                    Edit
                </flux:button>
            @endif

            @if($editAction && $deleteAction)
                <div class="h-px bg-gray-100 dark:bg-zinc-700 mx-2"></div>
            @endif

            @if($deleteAction)
                <flux:button 
                    x-on:click="$flux.modal('delete-confirm-{{ $item->id }}').show(); open = false"
                    variant="ghost" 
                    icon="trash"
                    class="w-full justify-start px-4 py-2.5 text-sm hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors !text-red-500">
                    Delete
                </flux:button>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($deleteAction)
        <flux:modal name="delete-confirm-{{ $item->id }}" class="md:w-96">
            <div class="flex flex-col gap-4 items-start justify-start">
                <p class="font-bold">Are you sure?</p>

                <p class="text-sm text-zinc-600 dark:text-zinc-400 text-start">
                    Your data will be deleted permanently. <br />This action cannot be undone.
                </p>

                <div class="flex items-center justify-end w-full gap-2 mt-4">
                    <flux:button 
                        variant="ghost" 
                        x-on:click="$flux.modal('delete-confirm-{{ $item->id }}').close()" 
                        size="sm">
                        Cancel
                    </flux:button>
                    
                    <flux:button 
                        variant="danger"
                        x-on:click="
                            $flux.modal('delete-confirm-{{ $item->id }}').close();
                            $wire.{{ $deleteAction }}({{ $item->id }});
                        "
                        size="sm">
                        Yes, Delete
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @endif
</div>
<?php

use Livewire\Volt\Component;

new class extends Component {
    public int $count = 0;

    public function increment()
    {
        $this->count++;
    }

    public function decrement()
    {
        $this->count--;
    }
};
?>

<div class="h-full">
    <flux:text class="mt-2 text-base">Counter</flux:text>

    <div class="flex flex-col items-center justify-center gap-4 h-full">
        <flux:text class="text-lg font-medium">{{ __('Counter') }}</flux:text>

        <flux:text class="text-5xl font-extrabold">{{ $count }}</flux:text>

        <div class="flex gap-4">
            <flux:button wire:click="decrement" :disabled="$count <= 0">Decrement</flux:button>
            <flux:button wire:click="increment">Increment</flux:button>
        </div>
    </div>



</div>
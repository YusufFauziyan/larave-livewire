@props([
    'type' => 'text',
    'placeholder' => '',
    'wireModel' => null,
])

<input
    type="{{ $type }}"
    placeholder="{{ $placeholder }}"
    {{ $wireModel ? "wire:model.live.debounce.300ms=$wireModel" : '' }}
    {{ $attributes->merge([
        'class' => 'w-full pl-10 pr-20 py-3 border border-zinc-300 dark:border-zinc-600 
                    rounded-lg text-sm focus:outline-none focus:ring-2 
                    focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent 
                    transition-all bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white 
                    placeholder-zinc-500 dark:placeholder-zinc-400'
    ]) }}
/>

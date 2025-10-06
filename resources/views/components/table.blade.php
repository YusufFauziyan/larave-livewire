@props([
    /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection $items */
    'items',
    /** @var array<string,string> $columns  key = field, value = label */
    'columns' => [],
    /** @var array<string, \Closure> $customColumns */
    'customColumns' => [],
    'columnClasses' => [],
    /** @var array<int,string> $sortableColumns */
    'sortableColumns' => [],
    'sortField' => null,
    'sortDirection' => 'asc',
    'perPage' => null,
    'search' => null,
])

<div class="bg-white dark:bg-zinc-800 rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-900">
                <tr>
                    @foreach($columns as $field => $label)
                        @php
                            $isSortable = in_array($field, $sortableColumns);
                        @endphp
                        <th
                            wire:key="header-{{ $field }}"
                            @if($isSortable) wire:click="sortBy('{{ $field }}')" @endif
                            class="group px-6 py-4 text-left text-xs font-semibold text-zinc-700 dark:text-zinc-300 tracking-wider
                                   {{ $isSortable ? 'cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors' : '' }}">
                            <div class="flex items-center gap-2">
                                <span>{{ $label }}</span>
                                @if($isSortable && $sortField === $field)
                                    <div class="flex flex-col">
                                        <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'text-zinc-400 dark:text-zinc-600' }}"
                                            fill="currentColor" viewBox="0 0 20 20"><path d="M5 10l5-5 5 5H5z" /></svg>
                                        <svg class="w-3 h-3 -mt-1 {{ $sortDirection === 'desc' ? '' : 'text-zinc-400 dark:text-zinc-600' }}"
                                            fill="currentColor" viewBox="0 0 20 20"><path d="M15 10l-5 5-5-5h10z" /></svg>
                                    </div>
                                @endif
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>

           <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700 shadow-sm">
                {{-- Loading Skeleton --}}
                {{-- 
                @if($perPage)
                    <tr wire:loading>
                        <td colspan="{{ count($columns) }}" class="px-6 py-4">
                            @for($i = 0; $i < $perPage; $i++)
                                <div wire:key="row-{{ $i }}" class="flex items-center space-x-4 py-4 {{ $i > 0 ? 'border-t border-zinc-200 dark:border-zinc-700' : '' }}">
                                    @foreach($columns as $col)
                                        <div class="flex-1">
                                            <div class="h-4 bg-zinc-200 dark:bg-zinc-700 rounded animate-pulse"></div>
                                        </div>
                                    @endforeach
                                </div>
                            @endfor
                        </td>
                    </tr>
                @endif
                 --}}

                @forelse($items as $item)
                    <tr wire:key="row-{{ $item->id }}"
                        class="hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors border-x border-zinc-200 dark:border-zinc-700">
                        @foreach($columns as $field => $label)
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-white {{ $columnClasses[$field] ?? '' }}">
                                @if(isset($customColumns[$field]) && is_callable($customColumns[$field]))
                                    {!! $customColumns[$field]($item) !!}
                                @else
                                    {{ data_get($item, $field, '-') }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr wire:loading.remove>
                        <td colspan="{{ count($columns) }}" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center min-h-[30vh]">
                                <svg class="w-16 h-16 text-zinc-400 dark:text-zinc-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>

                                <p class="text-zinc-600 dark:text-zinc-400 font-medium">No records found</p>

                                @if(!empty($search ?? null))
                                   <flux:button variant="ghost" wire:click="$set('search','')" size="xs" class="mt-2 !text-zinc-500 !dark:text-zinc-200">
                                       Reset search
                                   </flux:button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($items instanceof \Illuminate\Contracts\Pagination\Paginator && $items->hasPages())
        <div class="bg-zinc-50 dark:bg-zinc-900 px-6 py-4 border-t border-zinc-200 dark:border-zinc-700">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-zinc-600 dark:text-zinc-400">
                    Showing
                    <span class="font-semibold text-zinc-900 dark:text-white">{{ $items->firstItem() }}</span>
                    to
                    <span class="font-semibold text-zinc-900 dark:text-white">{{ $items->lastItem() }}</span>
                    of
                    <span class="font-semibold text-zinc-900 dark:text-white">{{ $items->total() }}</span>
                    results
                </div>

                <div class="flex items-center gap-3">
                    <!-- Per Page Selector -->
                    <select wire:model.live="perPage"
                        class="bg-white dark:bg-zinc-700 border border-zinc-300 dark:border-zinc-600 rounded-lg px-3 py-1.5 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:border-zinc-400 dark:hover:border-zinc-500 focus:outline-none focus:ring-2 focus: dark:focus: transition-all cursor-pointer">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>

                    <!-- Custom Pagination Links -->
                    <div class="flex items-center gap-1">
                        {{-- Previous --}}
                        @if(method_exists($items, 'onFirstPage') && $items->onFirstPage())
                            <span class="px-3 py-1.5 text-sm font-medium text-zinc-400 dark:text-zinc-600 bg-zinc-100 dark:bg-zinc-800 rounded-lg cursor-not-allowed">
                                ‹
                            </span>
                        @else
                            <button wire:click="previousPage" class="px-3 py-1.5 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-600 border border-zinc-300 dark:border-zinc-600 rounded-lg">
                                ‹
                            </button>
                        @endif

                        {{-- Page Numbers --}}
                        @php
                            $currentPage = $items->currentPage();
                            $lastPage = $items->lastPage();
                            $start = max(1, $currentPage - 1);
                            $end = min($lastPage, $currentPage + 1);
                        @endphp

                        @if($start > 1)
                            <button wire:click="gotoPage(1)" class="px-3 py-1.5 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-600 border border-zinc-300 dark:border-zinc-600 rounded-lg">
                                1
                            </button>
                            @if($start > 2)
                                <span class="px-2 text-zinc-500 dark:text-zinc-400">...</span>
                            @endif
                        @endif

                        @for($page = $start; $page <= $end; $page++)
                            @if($page == $currentPage)
                                <span class="px-3 py-1.5 text-sm font-medium border border-zinc-200 rounded-lg">{{ $page }}</span>
                            @else
                                <button wire:click="gotoPage({{ $page }})" class="px-3 py-1.5 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-600 border border-zinc-300 dark:border-zinc-600 rounded-lg">
                                    {{ $page }}
                                </button>
                            @endif
                        @endfor

                        @if($end < $lastPage)
                            @if($end < $lastPage - 1)
                                <span class="px-2 text-zinc-500 dark:text-zinc-400">...</span>
                            @endif
                            <button wire:click="gotoPage({{ $lastPage }})" class="px-3 py-1.5 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-600 border border-zinc-300 dark:border-zinc-600 rounded-lg">
                                {{ $lastPage }}
                            </button>
                        @endif

                        {{-- Next --}}
                        @if($items->hasMorePages())
                            <button wire:click="nextPage" class="px-3 py-1.5 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-600 border border-zinc-300 dark:border-zinc-600 rounded-lg">
                                ›
                            </button>
                        @else
                            <span class="px-3 py-1.5 text-sm font-medium text-zinc-400 dark:text-zinc-600 bg-zinc-100 dark:bg-zinc-800 rounded-lg cursor-not-allowed">
                                ›
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

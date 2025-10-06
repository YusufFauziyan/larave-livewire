@props([
    'id' => 'chart-' . uniqid(),
    'type' => 'line',
    'height' => 350,
    'series' => [],
    'options' => [],
])

<div id="{{ $id }}" class="apex-chart" style="min-height: {{ $height }}px;"></div>

@push('scripts')
    <script>
        document.addEventListener("livewire:load", () => initApexChart('{{ $id }}', @json($type), @json($series), @json($options)));
        document.addEventListener("livewire:navigated", () => initApexChart('{{ $id }}', @json($type), @json($series), @json($options)));
    </script>
@endpush

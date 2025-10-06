<x-layouts.app :title="__('ApexCharts Demo')">
    <div class="p-6 space-y-10">
        <h1 class="text-2xl font-bold mb-6">📊 ApexCharts Types Demo</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Line Chart --}}
            <div class="shadow-sm p-4 rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold mb-2">Line Chart</h2>
                <x-apex-chart
                    type="line"
                    :series="[['name' => 'Sales', 'data' => [10, 41, 35, 51, 49, 62, 69, 91, 148]]]"
                    :options="['categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep']]"
                />
            </div>

            {{-- Area Chart --}}
            <div class="shadow-sm p-4 rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold mb-2">Area Chart</h2>
                <x-apex-chart
                    type="area"
                    :series="[['name' => 'Revenue', 'data' => [44, 55, 41, 64, 22, 43, 21]]]"
                    :options="['categories' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']]"
                />
            </div>

            {{-- Bar Chart --}}
            <div class="shadow-sm p-4 rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold mb-2">Bar Chart</h2>
                <x-apex-chart
                    type="bar"
                    :series="[['name' => 'Customers', 'data' => [30, 40, 45, 50, 49, 60, 70, 91, 125]]]"
                    :options="['categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep']]"
                />
            </div>

            {{-- Pie Chart --}}
            <div class="shadow-sm p-4 rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold mb-2">Pie Chart</h2>
                <x-apex-chart
                    type="pie"
                    :series="[44, 55, 13, 43, 22]"
                    :options="['labels' => ['Apple', 'Mango', 'Banana', 'Papaya', 'Orange']]"
                />
            </div>

            {{-- Donut Chart --}}
            <div class="shadow-sm p-4 rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold mb-2">Donut Chart</h2>
                <x-apex-chart
                    type="donut"
                    :series="[44, 55, 41, 17, 15]"
                    :options="['labels' => ['Desktop', 'Tablet', 'Mobile', 'Smart TV', 'Other']]"
                />
            </div>

            {{-- Radial Bar Chart --}}
            <div class="shadow-sm p-4 rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold mb-2">Radial Bar Chart</h2>
                <x-apex-chart
                    type="radialBar"
                    :series="[70, 50, 30]"
                    :options="[
            'labels' => ['Product A', 'Product B', 'Product C'],
            'plotOptions' => ['radialBar' => ['dataLabels' => ['total' => ['show' => true]]]]
        ]"
                />
            </div>

            {{-- Heatmap Chart --}}
            <div class="shadow-sm p-4 rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold mb-2">Heatmap Chart</h2>
                <x-apex-chart
                    type="heatmap"
                    :series="[
            [
                'name' => 'Metric1',
                'data' => [
                    ['x' => 'Mon', 'y' => 80],
                    ['x' => 'Tue', 'y' => 50],
                    ['x' => 'Wed', 'y' => 30],
                    ['x' => 'Thu', 'y' => 40],
                    ['x' => 'Fri', 'y' => 100],
                    ['x' => 'Sat', 'y' => 20],
                ],
            ],
            [
                'name' => 'Metric2',
                'data' => [
                    ['x' => 'Mon', 'y' => 70],
                    ['x' => 'Tue', 'y' => 60],
                    ['x' => 'Wed', 'y' => 40],
                    ['x' => 'Thu', 'y' => 90],
                    ['x' => 'Fri', 'y' => 80],
                    ['x' => 'Sat', 'y' => 50],
                ],
            ],
        ]"
                    :options="[
            'plotOptions' => [
                'heatmap' => [
                    'colorScale' => [
                        'ranges' => [
                            ['from' => 0, 'to' => 50, 'color' => '#60a5fa', 'name' => 'Low'],
                            ['from' => 51, 'to' => 100, 'color' => '#facc15', 'name' => 'High'],
                        ],
                    ],
                ],
            ],
            'dataLabels' => ['enabled' => true],
        ]"
                />
            </div>
        </div>
    </div>
</x-layouts.app>

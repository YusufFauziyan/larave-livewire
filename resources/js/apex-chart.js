import ApexCharts from "apexcharts";

window.initApexChart = (id, type, series, options = {}) => {
    const el = document.getElementById(id);
    if (!el) return;

    // Destroy chart if exists
    if (el._apexchart) {
        el._apexchart.destroy();
        el._apexchart = null;
    }

    const defaultOptions = {
        chart: {
            type,
            height: options.height ?? 350,
            toolbar: { show: false },
        },
        stroke: { curve: "smooth" },
        legend: { show: true, position: "top" },
        series,
        xaxis: {
            categories: options.categories ?? [],
        },
        theme: {
            mode: document.documentElement.classList.contains("dark")
                ? "dark"
                : "light",
        },
        colors: options.colors ?? [
            "#0ea5e9",
            "#22c55e",
            "#facc15",
            "#ef4444",
            "#8b5cf6",
        ],
        dataLabels: { enabled: options.dataLabels ?? false },
        ...options,
    };

    const chart = new ApexCharts(el, defaultOptions);
    chart.render();

    el._apexchart = chart;
};

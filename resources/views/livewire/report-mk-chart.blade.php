<div>
    <div class="row mt-4">
        <div class="col-lg-12 mb-lg-0 mb-4">
            <div class="card z-index-2 h-100">
                <div class="card-header pb-0 pt-3 bg-transparent">
                    <h6 class="text-capitalize">Barang Masuk & Keluar</h6>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var ctx1 = document.getElementById("chart-line").getContext("2d");

            var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
            gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
            gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
            gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');

            var gradientStroke2 = ctx1.createLinearGradient(0, 230, 0, 50);
            gradientStroke2.addColorStop(1, 'rgba(255, 193, 7, 0.2)');
            gradientStroke2.addColorStop(0.2, 'rgba(255, 193, 7, 0.0)');
            gradientStroke2.addColorStop(0, 'rgba(255, 193, 7, 0)');

            new Chart(ctx1, {
                type: "line",
                data: {
                    labels: @json($months),
                    datasets: [
                        {
                            label: "Barang Masuk",
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 0,
                            borderColor: "#5e72e4", // Primary
                            backgroundColor: gradientStroke1,
                            fill: true,
                            data: @json($entriesData),
                            maxBarThickness: 6
                        },
                        {
                            label: "Barang Keluar",
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 0,
                            borderColor: "#ffc107", // Warning
                            backgroundColor: gradientStroke2,
                            fill: true,
                            data: @json($exitsData),
                            maxBarThickness: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    scales: {
                        y: {
                            grid: {
                                drawBorder: false,
                                display: true,
                                drawOnChartArea: true,
                                drawTicks: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                display: true,
                                padding: 10,
                                color: '#fbfbfb',
                                font: {
                                    size: 11,
                                    family: "Open Sans",
                                    style: 'normal',
                                    lineHeight: 2
                                },
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                display: true,
                                color: '#ccc',
                                padding: 20,
                                font: {
                                    size: 11,
                                    family: "Open Sans",
                                    style: 'normal',
                                    lineHeight: 2
                                },
                            }
                        },
                    },
                },
            });
        });
    </script>
</div>

<div>
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 mx-auto mt-4">
            <div class="card shadow-sm p-3">
                <div class="card-body">
                    <div class="row mt-4">
                        <!-- Diagram Pie di Kiri -->
                        <div class="col-4 d-flex">
                            <canvas id="stockChart" width="100%" height="150"></canvas>
                        </div>
                        
                        <!-- Informasi Total Produk & Penjualan di Kanan -->
                        <div class="col-4">
                            <h5 class="font-weight-bolder">Report</h5>
                            <h6 class="text-uppercase font-weight-bold">Jumlah Total Produk: <strong class="text-dark"> {{ $totalProducts }}</strong></h6>
                            <h6 class="text-uppercase font-weight-bold">Jumlah Total Stok: <strong class="text-dark">{{ $totalStock }}</strong></h6>
                            <h6 class="text-uppercase font-weight-bold">Total Belum Terjual: <strong class="text-primary">Rp {{ number_format($totalPriceProducts, 0, ',', '.') }}</strong></h6>
                            <h6 class="text-uppercase font-weight-bold">Total Penjualan: <strong class="text-success">Rp {{ number_format($totalSales, 0, ',', '.') }}</strong></h6>
                        </div>

                        <div class="col-4">
                            <h5 class="font-weight-bolder">Cetak Report</h5>
                            <button class="btn btn-icon btn-success" type="button">
                                <span class="btn-inner--icon"><i class="ni ni-bag-17"></i></span>
                                <span class="btn-inner--text"> SEMUA DATA</span>
                            </button>

                            <button class="btn btn-icon btn-primary" type="button">
                                <span class="btn-inner--icon block"><i class="ni ni-bag-17"></i></span>
                                <span class="btn-inner--text block"> BULAN INI</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('stockChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Stock Masuk', 'Stock Keluar'],
                datasets: [{
                    data: [{{ $stockEntri }}, {{ $stockExit }}],
                    backgroundColor: ['#36A2EB', '#FF6384'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>

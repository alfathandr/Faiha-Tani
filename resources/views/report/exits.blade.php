<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 20px;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #007bff;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        h3 {
            color: #28a745;
            margin-top: 25px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            **text-align: center;
        }
        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .no-data {
            color: #dc3545;
            font-style: italic;
        }
        /* Tambahan gaya untuk ikon (opsional) */
        .icon {
            margin-right: 5px;
        }
        /* Gaya tombol print */
        .print-button {
            background-color: #17a2b8;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }
        .print-button:hover {
            background-color: #138496;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>{{ $title }}</h2>
            <button class="print-button" onclick="window.print()">
                <i class="fas fa-print icon"></i> Cetak Laporan
            </button>
        </div>

        <h3><i class="fas fa-sign-out-alt icon"></i> Stok Keluar</h3>
        @if ($stockExits->isNotEmpty())
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag icon"></i> ID</th>
                            <th><i class="fas fa-tag icon"></i> Nama Produk</th>
                            <th><i class="fas fa-truck icon"></i> Pemasok</th>
                            <th><i class="fas fa-address-book icon"></i> Kontak Pemasok</th>
                            <th><i class="fas fa-minus-circle icon"></i> Kuantitas</th>
                            <th><i class="fas fa-money-bill-wave icon"></i> Harga</th>
                            <th><i class="fas fa-calendar-alt icon"></i> Tanggal Keluar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stockExits as $exit)
                            <tr>
                                <td>{{ $exit->id }}</td>
                                <td>{{ $exit->product->name }}</td>
                                {{-- Menampilkan nama supplier --}}
                                <td>
                                    @if ($exit->product->supplier) {{-- Cek apakah produk memiliki supplier terkait --}}
                                        {{ $exit->product->supplier->name }}
                                    @else
                                        -
                                    @endif
                                </td>
                                {{-- Menampilkan kontak supplier --}}
                                <td>
                                    @if ($exit->product->supplier) {{-- Cek apakah produk memiliki supplier terkait --}}
                                        {{ $exit->product->supplier->contact }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $exit->quantity }}</td>
                                <td>Rp {{ number_format($exit->price, 0, ',', '.') }},-</td>
                                <td>{{ $exit->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="no-data"><i class="fas fa-exclamation-triangle icon"></i> Tidak ada data stok keluar.</p>
        @endif

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>{{ $title }}</h2>

        <h3>Stok Masuk</h3>
        @if ($stockEntries->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Produk</th>
                        <th>Kuantitas</th>
                        <th>Harga</th>
                        <th>Tanggal Masuk</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stockEntries as $entry)
                        <tr>
                            <td>{{ $entry->id }}</td>
                            <td>{{ $entry->product->name }}</td>
                            <td>{{ $entry->quantity }}</td>
                            <td>Rp {{ number_format($entry->price, 0, ',', '.') }}</td>
                            <td>{{ $entry->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada data stok masuk.</p>
        @endif

        <h3 class="mt-3">Stok Keluar</h3>
        @if ($stockExits->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Produk</th>
                        <th>Kuantitas</th>
                        <th>Harga</th>
                        <th>Tanggal Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stockExits as $exit)
                        <tr>
                            <td>{{ $exit->id }}</td>
                            <td>{{ $exit->product->name }}</td>
                            <td>{{ $exit->quantity }}</td>
                            <td>Rp {{ number_format($exit->price, 0, ',', '.') }}</td>
                            <td>{{ $exit->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada data stok keluar.</p>
        @endif

        @if ($products)
            <h3 class="mt-3">Data Produk</h3>
            @if ($products->isNotEmpty())
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Produk</th>
                            <th>Deskripsi</th>
                            <th>Harga</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->description }}</td>
                                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td>{{ $product->stock }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Tidak ada data produk.</p>
            @endif
        @endif
    </div>
</body>
</html>
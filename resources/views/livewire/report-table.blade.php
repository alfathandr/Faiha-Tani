<div>
    @if (session()->has('message'))
        <div class="alert alert-light px-2" role="alert">
            <strong>Berhasil!</strong> {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-warning px-2" role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-warning px-2" role="alert">
            <strong>Gagal!</strong> Terjadi kesalahan, periksa kembali inputan Anda.
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4" id="tabelBarangMasuk">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6>Laporan Barang Masuk / Keluar</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">

                        <div class="px-4 mt-4">
                            <span>Tampilkan data berdasarkan rentang tanggal:</span>
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="start-date" class="form-label">Dari tanggal</label>
                                        <input type="date" id="start-date" wire:model.live.defer="startDate" class="form-control">
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <span class="fw-bold">-</span>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="end-date" class="form-label">Sampai tanggal</label>
                                        <input type="date" id="end-date" wire:model.live.defer="endDate" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Pilih Data</label>
                                        <select class="form-control" wire:model.live.defer="data"> {{-- Add .live here for instant filtering --}}
                                            <option value="">-- Tampilkan Semua Data --</option>
                                            <option value="Entri">Data Masuk</option>
                                            <option value="Exit">Data Keluar</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Barang</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Harga</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Stok</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            {{-- Pastikan ada product dan image sebelum menampilkan --}}
                                            @if ($transaction->product && $transaction->product->image)
                                                <img src="{{ asset('storage/' . $transaction->product->image) }}" class="avatar avatar-sm me-3" alt="{{ $transaction->product->name }}">
                                            @else
                                                {{-- Gambar placeholder jika tidak ada gambar atau produk tidak ditemukan --}}
                                                <img src="{{ asset('path/to/placeholder-image.jpg') }}" class="avatar avatar-sm me-3" alt="No Image">
                                            @endif
                                            <div>
                                                {{-- Pastikan ada product sebelum menampilkan nama --}}
                                                <h6 class="mb-0 text-sm">
                                                    @if ($transaction->product)
                                                        {{ $transaction->product->name }}
                                                    @else
                                                        Produk Tidak Ditemukan
                                                    @endif
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($transaction->product)
                                            Rp {{ number_format($transaction->product->price, 0, ',', '.') }},-
                                        @else
                                            Rp 0,-
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $transaction->quantity }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $transaction->type == 'Masuk' ? 'bg-success' : 'bg-danger' }}">{{ $transaction->type }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if ($transaction->product)
                                            {{ $transaction->product->stock }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                @empty
                                    <tr>
                                        {{-- Sesuaikan colspan dengan jumlah kolom total di tabel Anda (9 kolom: No, Barang, Harga, Deskripsi, Supplier, Kontak, Stok, Tipe, Stok Terbaru, Tanggal) --}}
                                        <td colspan="9" class="text-center text-secondary py-3">Belum ada aktivitas barang.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

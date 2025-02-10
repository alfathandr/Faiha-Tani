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
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tersedia</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <img src="{{ asset('storage/' . $transaction->product->image) }}" class="avatar avatar-sm me-3" alt="{{ $transaction->product->name }}">
                                                <div>
                                                    <h6 class="mb-0 text-sm">{{ $transaction->product->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Rp. {{ number_format($transaction->product->price, 0, ',', '.') }}</td>
                                        <td class="text-center">{{ $transaction->quantity }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $transaction->type == 'Masuk' ? 'bg-success' : 'bg-danger' }}">{{ $transaction->type }}</span>
                                        </td>
                                        <td class="text-center">{{ $transaction->product->stock }}</td>
                                        <td class="text-center">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-secondary py-3">Belum ada aktivitas barang.</td>
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

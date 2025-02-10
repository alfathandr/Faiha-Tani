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
                        <h6>Barang Keluar</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                            <div class="row align-items-cente px-4 pt-4">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="search" class="form-label">Cari Produk</label>
                                        <input type="text" id="search" placeholder="Masukkan nama barang ... " wire:model.live.defer="search" class="form-control">
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
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tersedia</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah yang ingin dilepas</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <img src="{{ asset('storage/' . $product->image) }}" class="avatar avatar-sm me-3" alt="{{ $product->name }}">
                                                <div>
                                                    <h6 class="mb-0 text-sm">{{ $product->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Rp. {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td class="text-center">{{ $product->stock }}</td>
                                        <td class="align-middle text-center">
                                            <div class="form-group pt-2">
                                                <input class="form-control w-35 mx-auto" type="number"
                                                    wire:model.defer="exitQuantities.{{ $product->id }}"
                                                    min="1" max="{{ $product->stock }}">
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <button class="btn btn-sm btn-icon btn-primary px-3"
                                                wire:click="removeStock({{ $product->id }})">
                                                <span class="btn-inner--icon"><i class="fa-solid fa-share-from-square"></i> Lepas </span>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-secondary py-3">Data barang belum tersedia.</td>
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

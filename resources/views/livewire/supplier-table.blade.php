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
                @if (!$showAddForm && !$showEditForm)
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h6>Pemasok</h6>
                            <button class="btn btn-dark" type="button" wire:click="$set('showAddForm', true)">
                                <i class="fa fa-plus"></i> Tambah
                            </button>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="px-4 mt-4">
                                <div class="row align-items-center">
                                <div class="row mb-3"> <div class="col-md-4"> <div class="form-group">
                                    <label for="search" class="form-label">Cari Pemasok</label>
                                    <input type="text" id="search" placeholder="Masukkan nama pemasok ... " wire:model.live.debounce.500ms="search" class="form-control">
                                </div>
                            </div>
                        </div>
                            <div class="table-responsive p-0">
                                <table class="table align-items-top mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kontak</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Alamat</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Barang</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Update Pada</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                    </tr>
                                </thead>
                                    <tbody>
                                        @forelse ($suppliers as $supplier)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $supplier->name }}</td>
                                            <td>{{ $supplier->contact }}</td>
                                            <td>{{ $supplier->address }}</td>
                                            <td class="align-top" >
                                                {{-- Menampilkan data produk yang menggunakan supplier ini --}}
                                                @if ($supplier->products->count() > 0)
                                                    <ul class="list-unstyled mb-0 align-top"> {{-- Menggunakan unordered list untuk daftar ke bawah --}}
                                                        @foreach ($supplier->products as $product)
                                                            <li>
                                                                <i class="fa fa-tag me-1"></i>{{ $loop->iteration }}. {{ $product->name }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <span class="text-muted">Tidak ada produk terkait</span>
                                                @endif
                                            </td>
                                            <td>{{ $product->updated_at->format('d-m-Y H:i') }}</td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-primary" type="button" wire:click="update({{ $supplier->id }})">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" type="button" wire:click="confirmDelete({{ $supplier->id }})">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                {{-- Sesuaikan colspan dengan jumlah kolom total di tabel Anda --}}
                                                {{-- Ada 6 kolom: No, Nama Supplier, Kontak, Alamat, Produk, Aksi --}}
                                                <td colspan="6" class="text-center text-secondary py-3">Data supplier belum tersedia.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($showAddForm)
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6>Tambah Pemasok</h6>
                            <button class="btn btn-warning" type="button" wire:click="$set('showAddForm', false)">
                                Batal
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Nama</label>
                                    <input class="form-control" type="text" wire:model="name" required>
                                    @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label>Kontak</label>
                                    <input class="form-control" type="text" wire:model="contact" required>
                                    @error('contact') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-12">
                                    <label>Alamat</label>
                                    <input class="form-control" type="text" wire:model="address" required>
                                    @error('address') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-12 mt-3">
                                    <button class="btn btn-dark btn-lg w-100" wire:click="addSupplier">
                                        Masukkan Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($showEditForm)
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6>Edit Pemasok</h6>
                            <button class="btn btn-warning" type="button" wire:click="$set('showEditForm', false)">
                                Batal
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Nama</label>
                                    <input class="form-control" type="text" wire:model="name" required>
                                    @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label>Kontak</label>
                                    <input class="form-control" type="text" wire:model="contact" required>
                                    @error('contact') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-12">
                                    <label>Alamat</label>
                                    <input class="form-control" type="text" wire:model="address" required>
                                    @error('address') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-12 mt-3">
                                <button class="btn btn-dark btn-lg w-100" wire:click="updateSupplier">
                                    Perbarui Data
                                </button>
                                </div>

                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

<!-- Modal -->
<div class="modal fade @if($showDeleteModal) show d-block @endif" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Hapus</h5>
            </div>
            <div class="modal-body">
               Apakah anda yakin ingin menghapus supplier <strong>{{ $supplierNameToDelete }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" wire:click="$set('showDeleteModal', false)">Batal</button>
                <button type="button" class="btn btn-danger" wire:click="delete">Hapus</button>
            </div>
        </div>
    </div>
</div>


</div>

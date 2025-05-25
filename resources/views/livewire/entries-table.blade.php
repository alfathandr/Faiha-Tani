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
                    <!-- Tabel Barang Masuk -->
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h6>Barang Masuk</h6>
                            <button class="btn btn-dark" type="button" wire:click="$set('showAddForm', true)">
                                <i class="fa fa-plus"></i> Tambah
                            </button>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="px-4 mt-4">
                                <div class="row align-items-center">
                                <div class="row mb-3"> <div class="col-md-4"> <div class="form-group">
                                    <label for="search" class="form-label">Cari Produk</label>
                                    <input type="text" id="search" placeholder="Masukkan nama barang ... " wire:model.live.debounce.500ms="search" class="form-control">
                                </div>
                            </div>
                        </div>
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="cursor: pointer;" wire:click="sortBy('name')">
                                            Nama Barang
                                            @if ($sortColumn === 'name')
                                                @if ($sortDirection === 'asc')
                                                    <i class="fa fa-sort-up"></i>
                                                @else
                                                    <i class="fa fa-sort-down"></i>
                                                @endif
                                            @else
                                                <i class="fa fa-sort"></i>
                                            @endif
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2" style="cursor: pointer;" wire:click="sortBy('price')">
                                            Harga
                                            @if ($sortColumn === 'price')
                                                @if ($sortDirection === 'asc')
                                                    <i class="fa fa-sort-up"></i>
                                                @else
                                                    <i class="fa fa-sort-down"></i>
                                                @endif
                                            @else
                                                <i class="fa fa-sort"></i>
                                            @endif
                                        </th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 wrap-text">Deskripsi</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pemasok</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kontak Pemasok</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="cursor: pointer;" wire:click="sortBy('stock')">
                                            Stok
                                            @if ($sortColumn === 'stock')
                                                @if ($sortDirection === 'asc')
                                                    <i class="fa fa-sort-up"></i>
                                                @else
                                                    <i class="fa fa-sort-down"></i>
                                                @endif
                                            @else
                                                <i class="fa fa-sort"></i>
                                            @endif
                                        </th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="cursor: pointer;" wire:click="sortBy('updated_at')">
                                            Update pada
                                            @if ($sortColumn === 'updated_at')
                                                @if ($sortDirection === 'asc')
                                                    <i class="fa fa-sort-up"></i>
                                                @else
                                                    <i class="fa fa-sort-down"></i>
                                                @endif
                                            @else
                                                <i class="fa fa-sort"></i>
                                            @endif
                                        </th>
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
                                                        <h6 class="mb-0 text-sm" style="white-space: normal; overflow-wrap: break-word;">{{ $product->name }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Rp {{ number_format($product->price, 0, ',', '.') }},-</td>
                                            <td style="white-space: normal; overflow-wrap: break-word;">{{ $product->description }}</td>
                                            {{-- Menampilkan nama supplier --}}
                                            <td>
                                                @if ($product->supplier) {{-- Cek apakah produk memiliki supplier terkait --}}
                                                    {{ $product->supplier->name }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            {{-- Menampilkan kontak supplier --}}
                                            <td>
                                                @if ($product->supplier) {{-- Cek apakah produk memiliki supplier terkait --}}
                                                    {{ $product->supplier->contact }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $product->stock }}</td>
                                            <td class="text-center">{{ $product->updated_at->format('d-m-Y H:i') }}</td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-primary" type="button" wire:click="update({{ $product->id }})">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" type="button" wire:click="confirmDelete({{ $product->id }})">
                                                    <i class="fa fa-trash"></i>
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
                @endif

                @if ($showAddForm)
                    <!-- Form Tambah Barang -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6>Tambah Barang</h6>
                            <button class="btn btn-warning" type="button" wire:click="$set('showAddForm', false)">
                                Batal
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-16">
                                    <label>Nama Barang</label>
                                    <input class="form-control" type="text" wire:model="name" required>
                                    @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Harga</label>
                                    <input class="form-control" type="number" wire:model="price" required>
                                    @error('price') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Stok</label>
                                    <input class="form-control" type="number" wire:model="stock" required>
                                    @error('stock') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Gambar</label>
                                    <input class="form-control" type="file" wire:model="image" required>
                                    @error('image') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Pilih Supplier</label>
                                    <select class="form-control" wire:model="supplier_id" required>
                                        <option value="">-- Pilih Supplier --</option>
                                        @foreach($suppliersList as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-12">
                                    <label>Deskripsi</label>
                                    <textarea class="form-control" rows="5" wire:model="description"></textarea>
                                    @error('description') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-12 mt-3">
                                    <button class="btn btn-dark btn-lg w-100" wire:click="addProduct">
                                        Masukkan Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($showEditForm)
                    <!-- Form Edit Barang -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6>Edit Barang</h6>
                            <button class="btn btn-warning" type="button" wire:click="$set('showEditForm', false)">
                                Batal
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Nama Barang</label>
                                    <input class="form-control" type="text" wire:model="name">
                                    @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Harga</label>
                                    <input class="form-control" type="number" wire:model="price">
                                    @error('price') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Stok</label>
                                    <input class="form-control" type="number" wire:model="stock">
                                    @error('stock') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Gambar</label>
                                    <input class="form-control" type="file" wire:model="image">
                                    @error('image') <span class="text-danger text-sm">{{ $message }}</span> @enderror

                                    {{-- Tampilkan gambar lama jika ada saat mode edit --}}
                                    @if ($selectedProduct && $selectedProduct->image)
                                        <img src="{{ asset('storage/' . $selectedProduct->image) }}" alt="Gambar Produk" class="img-thumbnail mt-2" style="max-width: 100px;">
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label>Pilih Supplier</label>
                                    <select class="form-control" wire:model="supplier_id">
                                        <option value="">-- Pilih Supplier --</option>
                                        @foreach($suppliersList as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-12">
                                    <label>Deskripsi</label>
                                    <textarea class="form-control" rows="5" wire:model="description"></textarea>
                                    @error('description') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-12 mt-3">
                                    <button class="btn btn-dark btn-lg w-100" wire:click="updateProduct" wire:loading.attr="disabled" wire:target="image">
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
               Apakah anda yakin ingin menghapus produk <strong>{{ $productNameToDelete }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" wire:click="$set('showDeleteModal', false)">Batal</button>
                <button type="button" class="btn btn-danger" wire:click="delete">Hapus</button>
            </div>
        </div>
    </div>
</div>


</div>

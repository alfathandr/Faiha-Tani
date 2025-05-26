
<div>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h6>Data Stok Barang</h6>
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
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 wrap-text">Stok</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 wrap-text">Terjual</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 wrap-text">Total</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 wrap-text">Harga Terjual</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 wrap-text">Harga Belum Terjual</th>
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
                                            <td class="text-center text-danger">{{ $product->stock }}</td>
                                            <td class="text-center text-info">{{ $product->stockExits()->sum('quantity') }}</td>
                                            <td class="text-center text-info">{{ $product->stockExits()->sum('quantity') }}</td>
                                            {{-- Kolom stok terkoreksi --}}
                                            <td class="text-center">
                                                {{ $this->getCorrectedStock($product) }}
                                            </td>
                                            <td class="text-center text-info">Rp {{ number_format($product->price * $product->stockExits()->sum('quantity'), 0, ',', '.') }},-</td>
                                            <td class="text-center text-danger">Rp {{ number_format($product->price * $product->stockEntries()->sum('quantity'), 0, ',', '.') }},-</td>
                                            <td class="text-center">{{ $product->updated_at->format('d-m-Y H:i') }}</td>
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

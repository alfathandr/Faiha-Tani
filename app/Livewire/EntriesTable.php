<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\Supplier; // Import model Supplier
use App\Models\StockEntri;
use App\Models\StockExit;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // Import DB Facade
use Illuminate\Support\Facades\Log;

class EntriesTable extends Component
{
    use WithFileUploads;
    public $name, $description, $price,  $cost_price, $stock, $image;
    public $supplier_id; //
    public $selectedProduct;
    public $showEditForm = false;
    public $showAddForm = false;
    public $showDeleteModal = false;
    public $productIdToDelete;
    public $productNameToDelete;
    public $search = '';
    public $suppliersList = [];

    public $sortColumn = 'name'; // Default pengurutan berdasarkan nama
    public $sortDirection = 'asc'; // Default arah pengurutan ascending (A-Z)


    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'cost_price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'image' => 'image|max:2048',
        'supplier_id' => 'required|exists:suppliers,id',


    ];
    public function mount()
    {
        $this->suppliersList = Supplier::orderBy('name')->get();
    }

    public function addProduct()
    {
        // Validasi input
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'image|max:2048', // max 2MB
            'supplier_id' => 'required|exists:suppliers,id', // Pastikan ID supplier valid
        ]);

        try {
            DB::beginTransaction(); // Mulai transaksi

            // Simpan gambar jika ada
            $imagePath = null;
            if ($this->image) {
                $imagePath = $this->image->store('products', 'public');
            }

            // Simpan produk ke database
            $product = Product::create([
                'name' => $this->name,
                'description' => $this->description,
                'supplier_id' => $this->supplier_id, // Gunakan supplier_id
                'price' => $this->price,
                'cost_price' => $this->price,
                'stock' => $this->stock,
                'image' => $imagePath
            ]);

            // Catat stok awal ke tabel stock_entries
            // Pastikan StockEntri adalah nama model yang benar (misal: StockEntry)
            StockEntri::create([
                'product_id' => $product->id, // Gunakan ID produk yang baru saja dibuat
                'quantity' => $this->stock,
                'price' => $this->price
            ]);

            DB::commit(); // Simpan perubahan ke database

            session()->flash('message', 'Barang berhasil ditambahkan dan stok tercatat.');
            $this->resetForm();
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan transaksi jika ada error
            \Log::error('Error adding product: ' . $e->getMessage()); // Log error untuk debugging
            session()->flash('error', 'Terjadi kesalahan saat menambahkan barang: ' . $e->getMessage());
        }
    }


    public function update($id)
    {
        $this->selectedProduct = Product::findOrFail($id);
        $this->name = $this->selectedProduct->name;
        $this->description = $this->selectedProduct->description;
        $this->supplier_id = $this->selectedProduct->supplier_id; // Isi supplier_id dari relasi
        $this->price = $this->selectedProduct->price;
        $this->cost_price = $this->selectedProduct->cost_price;
        $this->stock = $this->selectedProduct->stock;
        $this->image = null; // Reset input file gambar saat edit, user harus upload ulang jika ingin ganti
        $this->showEditForm = true;
        $this->showAddForm = false;
    }


   public function updateProduct()
    {
        // Aturan validasi untuk update, gambar bisa nullable
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048', // nullable untuk update
            'supplier_id' => 'required|exists:suppliers,id', // Validasi: wajib dan harus ada di tabel suppliers
        ]);

        if (!$this->selectedProduct) {
            session()->flash('error', 'Produk tidak ditemukan.');
            return;
        }

        try {
            DB::beginTransaction(); // Mulai transaksi

            $oldStock = $this->selectedProduct->stock; // Simpan stok lama sebelum diperbarui
            $stockDifference = $this->stock - $oldStock; // Hitung selisih stok

            $dataToUpdate = [
                'name' => $this->name,
                'description' => $this->description,
                'supplier_id' => $this->supplier_id, // Gunakan supplier_id
                'price' => $this->price,
                'cost_price' => $this->cost_price,
                'stock' => $this->stock,
            ];

            // Handle gambar jika ada upload baru
            if ($this->image) {
                // Hapus gambar lama jika ada
                if ($this->selectedProduct->image) {
                    Storage::disk('public')->delete($this->selectedProduct->image);
                }
                $dataToUpdate['image'] = $this->image->store('products', 'public');
            }

            // Perbarui produk
            $this->selectedProduct->update($dataToUpdate);


            // Jika stok bertambah, masukkan ke StockEntri
            if ($stockDifference > 0) {
                StockEntri::create([
                    'product_id' => $this->selectedProduct->id,
                    'quantity' => $stockDifference, // Hanya jumlah tambahan
                    'price' => $this->price, // Gunakan harga produk saat ini
                    'type' => 'addition', // Kolom 'type' mungkin tidak ada di tabel Anda jika hanya untuk entri/keluar
                ]);
            }

            // Jika stok berkurang, masukkan ke StockExit
            if ($stockDifference < 0) {
                StockExit::create([
                    'product_id' => $this->selectedProduct->id,
                    'quantity' => abs($stockDifference), // Hanya jumlah pengurangan
                    'price' => $this->price, // Gunakan harga produk saat ini
                    'type' => 'reduction', // Kolom 'type' mungkin tidak ada di tabel Anda jika hanya untuk entri/keluar
                ]);
            }

            DB::commit(); // Simpan perubahan ke database

            session()->flash('message', 'Barang berhasil diperbarui.');
            $this->resetForm();
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan transaksi jika ada error
            \Log::error('Gagal memperbarui produk ID ' . $this->selectedProduct->id . ': ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat memperbarui barang: ' . $e->getMessage());
        }
    }



    public function confirmDelete($id)
    {
        $product = Product::find($id);

        if ($product) {
            $this->productIdToDelete = $id;
            $this->productNameToDelete = $product->name;
            $this->showDeleteModal = true; // Tampilkan modal
        }
    }

    public function delete()
    {
        if ($this->productIdToDelete) {
            $product = Product::find($this->productIdToDelete);
            if ($product) {
                Storage::disk('public')->delete($product->image);
                $product->delete();
                session()->flash('message', 'Barang "' . $this->productNameToDelete . '" berhasil dihapus.');
            } else {
                session()->flash('error', 'Barang tidak ditemukan.');
            }
        }

        // Reset modal
        $this->showDeleteModal = false;
        $this->productIdToDelete = null;
        $this->productNameToDelete = null;
    }

    private function resetForm()
    {
        // Tambahkan 'supplier_id' ke dalam properti yang di-reset
        $this->reset(['name', 'description', 'supplier_id', 'price',  'cost_price', 'stock', 'image', 'selectedProduct', 'showEditForm', 'showAddForm']);
        // Hapus $this->supplier dan $this->supplier_contact dari reset karena sudah tidak digunakan
    }

    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }


    // public function render()
    // {
    //     $products = Product::query()
    //         ->when($this->search, function ($query) {
    //             return $query->where('name', 'like', '%' . $this->search . '%');
    //         })
    //         ->orderBy($this->sortColumn, $this->sortDirection)
    //         ->get();

    //     return view('livewire.entries-table', [
    //         'products' => $products,
    //     ]);
    // }

    public function render()
    {
        $products = Product::query()
            ->when($this->search, function ($query) {
                // Cari berdasarkan nama, deskripsi, atau nama supplier (melalui relasi)
                return $query->where('products.name', 'like', '%' . $this->search . '%')
                             ->orWhere('products.description', 'like', '%' . $this->search . '%')
                             ->orWhereHas('supplier', function ($q) { // Mencari di relasi supplier
                                 $q->where('name', 'like', '%' . $this->search . '%');
                             });
            })
            ->orderBy($this->sortColumn, $this->sortDirection)
            // Tambahkan pagination jika ingin menampilkan data per halaman
            // ->paginate($this->perPage); // uncomment ini jika mau pakai pagination
            ->get(); // Jika tidak pakai pagination, gunakan get()

        return view('livewire.entries-table', [
            'products' => $products,
            // 'suppliersList' tidak perlu dikirim ulang karena sudah dimuat di mount()
        ]);
    }
}

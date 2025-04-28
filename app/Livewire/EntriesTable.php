<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\StockEntri;
use App\Models\StockExit;
use Illuminate\Support\Facades\Storage;

class EntriesTable extends Component
{
    use WithFileUploads;

    public $name, $description, $price, $stock, $image;
    public $selectedProduct;
    public $showEditForm = false;
    public $showAddForm = false;
    public $showDeleteModal = false;
    public $productIdToDelete;
    public $productNameToDelete;
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'image' => 'image|max:2048'
    ];

    public function addProduct()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|max:2048' // max 2MB
        ]);
    
        try {
            \DB::beginTransaction(); // Mulai transaksi
    
            // Simpan gambar jika ada
            $imagePath = $this->image ? $this->image->store('products', 'public') : null;
    
            // Simpan produk ke database
            $product = Product::create([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'stock' => $this->stock,
                'image' => $imagePath
            ]);
    
            // Catat stok awal ke tabel stock_entries
            StockEntri::create([
                'product_id' => $product->id, // Gunakan ID produk yang baru saja dibuat
                'quantity' => $this->stock,
                'price' => $this->price
            ]);
    
            \DB::commit(); // Simpan perubahan ke database
    
            session()->flash('message', 'Barang berhasil ditambahkan dan stok tercatat.');
            $this->resetForm();
        } catch (\Exception $e) {
            \DB::rollBack(); // Batalkan transaksi jika ada error
            session()->flash('error', 'Terjadi kesalahan saat menambahkan barang.');
        }
    }
    

    public function update($id)
    {
        $this->selectedProduct = Product::findOrFail($id);
        $this->name = $this->selectedProduct->name;
        $this->description = $this->selectedProduct->description;
        $this->price = $this->selectedProduct->price;
        $this->stock = $this->selectedProduct->stock;
        $this->showEditForm = true;
        $this->showAddForm = false;
    }

    public function updateProduct()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|max:2048' // max 2MB
        ]);
    
        if (!$this->selectedProduct) {
            session()->flash('error', 'Produk tidak ditemukan.');
            return;
        }
    
        try {
            $oldStock = $this->selectedProduct->stock; // Simpan stok lama sebelum diperbarui
            $stockDifference = $this->stock - $oldStock; // Hitung selisih stok
    
            if ($this->image) {
                // Hapus gambar lama jika ada
                if ($this->selectedProduct->image) {
                    Storage::disk('public')->delete($this->selectedProduct->image);
                }
                $this->selectedProduct->image = $this->image->store('products', 'public');
            }
    
            // Perbarui produk
            $this->selectedProduct->update([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'stock' => $this->stock,
                'image' => $this->selectedProduct->image,
            ]);
    
            // Jika stok bertambah, masukkan ke StockEntri
            if ($stockDifference > 0) {
                StockEntri::create([
                    'product_id' => $this->selectedProduct->id,
                    'quantity' => $stockDifference, // Hanya jumlah tambahan
                    'price' => $this->price,
                    'type' => 'addition',
                ]);
            }
    
            // Jika stok berkurang, masukkan ke StockExit
            if ($stockDifference < 0) {
                StockExit::create([
                    'product_id' => $this->selectedProduct->id,
                    'quantity' => abs($stockDifference), // Hanya jumlah pengurangan
                    'price' => $this->price,
                    'type' => 'reduction',
                ]);
            }
    
            session()->flash('message', 'Barang berhasil diperbarui.');
            $this->resetForm();
        } catch (\Exception $e) {
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
        $this->reset(['name', 'description', 'price', 'stock', 'image', 'selectedProduct', 'showEditForm', 'showAddForm']);
    }

    // public function render()
    // {
    //     return view('livewire.entries-table', [
    //         'products' => Product::latest()->get(),
    //     ]);
    // }

    public function render()
    {
        $products = Product::query()
            ->when($this->search, function ($query) {
                return $query->where('name', 'like', '%' . $this->search . '%'); // Pencarian berdasarkan nama produk
            })
            ->latest()
            ->get();

        return view('livewire.entries-table', [
            'products' => $products,
        ]);
    }
}

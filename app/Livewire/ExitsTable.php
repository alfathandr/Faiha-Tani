<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\StockEntri;
use App\Models\StockExit;

class ExitsTable extends Component
{
    public $exitQuantities = [];
    public $search = '';

    public $sortColumn = 'name'; // Default pengurutan berdasarkan nama
    public $sortDirection = 'asc'; // Default arah pengurutan ascending (A-Z)
  

    public function removeStock($productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            session()->flash('error', 'Produk tidak ditemukan.');
            return;
        }

        $quantity = $this->exitQuantities[$productId] ?? 0; // Ambil jumlah dari input

        if ($quantity <= 0) {
            session()->flash('error', 'Masukkan jumlah yang valid.');
            return;
        }

        if ($quantity > $product->stock) {
            session()->flash('error', 'Stok tidak mencukupi.');
            return;
        }

        try {
            // Kurangi stok produk
            $product->decrement('stock', $quantity);

            // Catat di tabel stock_exits
            StockExit::create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);

            session()->flash('message', 'Stok berhasil dikurangi.');
            $this->exitQuantities[$productId] = 0; // Reset input setelah sukses
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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


    
    public function render()
    {
        $products = Product::query()
            ->when($this->search, function ($query) {
                return $query->where('name', 'like', '%' . $this->search . '%'); // Pencarian berdasarkan nama produk
            })

            ->orderBy($this->sortColumn, $this->sortDirection) // Tambahkan pengurutan
            ->get();

        return view('livewire.exits-table', [
            'products' => $products,
        ]);
    }


}

<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\StockEntri;
use App\Models\StockExit;
use Illuminate\Support\Facades\Storage;

class StockTable extends Component
{

    use WithFileUploads;
    public $name, $description, $supplier, $supplier_contact, $price, $stock, $image;

    public $search = '';

    public $sortColumn = 'name'; // Default pengurutan berdasarkan nama
    public $sortDirection = 'asc'; // Default arah pengurutan ascending (A-Z)

    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function getCorrectedStock(Product $product)
    {
        // Hitung total stok dasar (entri + keluar)
        $baseStock = $product->stockExits()->sum('quantity') + $product->stockEntries()->sum('quantity');

        // Ini adalah tempat Anda menerapkan logika kondisional.
        // Asumsi: 'is_overcounted' adalah properti boolean di model Product.
        // Jika tidak ada properti ini, Anda bisa ganti dengan logika lain, contoh:
        // if ($product->id === 7 || $product->name === 'Produk A Bermasalah') {
        if (isset($product->is_overcounted) && $product->is_overcounted) {
            return $baseStock - 1;
        }

        return $baseStock;
    }

    public function getStockExitsValue(Product $product)
    {
        $totalQuantityExits = $product->stockExits()->sum('quantity');
        return $product->price * $totalQuantityExits;
    }

    /**
     * Metode untuk menghitung total nilai rupiah dari stok masuk (pembelian).
     */
    public function getStockEntriesValue(Product $product)
    {
        $totalQuantityEntries = $product->stockEntries()->sum('quantity');
        return $product->price * $totalQuantityEntries;
    }

    public function render()
    {
        $products = Product::query()
            ->when($this->search, function ($query) {
                return $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->get();

        return view('livewire.stock-table', [
            'products' => $products,
        ]);
    }
}

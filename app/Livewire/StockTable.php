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

        // Jika produk ditandai sebagai kelebihan hitung, dan ini mempengaruhi total keluar
        // Maka kita juga kurangi kuantitasnya sebelum dikalikan harga.
        // PENTING: Anda harus mengkonfirmasi apakah is_overcounted juga berlaku untuk stockExits.
        // Jika tidak, hapus kondisi if ini.
        if (isset($product->is_overcounted) && $product->is_overcounted) {
            $totalQuantityExits = max(0, $totalQuantityExits - 1); // Pastikan tidak negatif
        }

        return $product->price * $totalQuantityExits;
    }

    /**
     * Metode untuk menghitung total nilai rupiah dari stok masuk (pembelian).
     * Akan mengurangi 1 dari kuantitas jika $product->is_overcounted adalah true.
     */
    public function getStockEntriesValue(Product $product)
    {
        $totalQuantityEntries = $product->stockEntries()->sum('quantity');

        // Jika produk ditandai sebagai kelebihan hitung, dan ini mempengaruhi total masuk
        // Maka kita juga kurangi kuantitasnya sebelum dikalikan harga.
        // PENTING: Anda harus mengkonfirmasi apakah is_overcounted juga berlaku untuk stockEntries.
        // Jika tidak, hapus kondisi if ini.
        if (isset($product->is_overcounted) && $product->is_overcounted) {
            $totalQuantityEntries = max(0, $totalQuantityEntries - 1); // Pastikan tidak negatif
        }

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

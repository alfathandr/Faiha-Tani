<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\StockEntri; // Sesuaikan dengan nama model Anda
use App\Models\StockExit;  // Sesuaikan dengan nama model Anda

class StockTable extends Component
{
    use WithFileUploads;

    public $name, $description, $supplier, $supplier_contact, $price, $stock, $image; // Properti ini mungkin lebih cocok di komponen lain (misal: form produk)
    public $search = '';

    public $sortColumn = 'name';
    public $sortDirection = 'asc';

    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    // Metode untuk mendapatkan total kuantitas masuk
    public function getTotalEntriesQuantity(Product $product): int
    {
        return (int) $product->stockEntries()->sum('quantity');
    }

    // Metode untuk mendapatkan total kuantitas keluar
    public function getTotalExitsQuantity(Product $product): int
    {
        return (int) $product->stockExits()->sum('quantity');
    }

    // Metode untuk mendapatkan stok terkoreksi (stok aktual di kolom product)
    public function getCorrectedStock(Product $product): int
    {
        $currentStock = (int) $product->stock;

        if (isset($product->is_overcounted) && $product->is_overcounted) {
            return max(0, $currentStock - 1);
        }

        return $currentStock;
    }

    // Metode untuk mendapatkan nilai rupiah total stok keluar
    public function getTotalExitsValue(Product $product): int
    {
<<<<<<< HEAD
        $totalQuantityExits = $this->getTotalExitsQuantity($product);
        $productPrice = (int) $product->price;

        return (int) ($productPrice * $totalQuantityExits);
    }

    // Metode untuk mendapatkan nilai rupiah total stok masuk (DIPERBAIKI UNTUK KEJELASAN)
    public function getTotalEntriesValue(Product $product): int
    {
        $totalQuantityEntries = $this->getTotalEntriesQuantity($product);
        $productPrice = (int) $product->price;

        // Fungsi ini seharusnya hanya mengembalikan nilai total dari barang masuk
        return (int) ($productPrice * $totalQuantityEntries);
    }

    // --- FUNGSI BARU: MENGHITUNG NILAI STOK NETO (MASUK - KELUAR) ---
    public function getNetStockValue(Product $product): int
    {
        $totalEntriesValue = $this->getTotalEntriesValue($product);
        $totalExitsValue = $this->getTotalExitsValue($product);

        // Mengurangi total nilai keluar dari total nilai masuk
        return (int) ($totalEntriesValue - $totalExitsValue);
    }

    // --- FUNGSI UNTUK MENGHITUNG NILAI STOK SAAT INI (BERDASARKAN getCorrectedStock) ---
    public function getCurrentStockValue(Product $product): int
    {
        $currentQuantity = $this->getCorrectedStock($product);
        $productPrice = (int) $product->price;

        return (int) ($productPrice * $currentQuantity);
=======
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
>>>>>>> 28dfa362380547c2457d15fd9ab47099ef782d26
    }
    public function render()
    {
        $products = Product::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->with(['stockEntries', 'stockExits']) // Eager load relasi
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->get();

        return view('livewire.stock-table', [
            'products' => $products,
        ]);
    }
}
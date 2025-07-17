<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\StockExit;
use Carbon\Carbon; // Pastikan Carbon sudah diimpor

class ExitsTable extends Component
{
    public $exits_date; // Properti publik untuk menyimpan tanggal dan waktu
    public $exitQuantities = [];
    public $search = '';

    public $sortColumn = 'name';
    public $sortDirection = 'asc';

    protected $rules = [
        'exits_date' => 'date', // Validasi hanya untuk format tanggal/waktu yang valid
    ];

    public function mount()
    {
        // PENTING: Menggunakan Carbon::now() untuk mendapatkan TANGGAL DAN WAKTU SAAT INI.
        // toDateTimeLocalString() memformatnya agar kompatibel dengan input type="datetime-local"
        $this->exits_date = Carbon::now()->toDateTimeLocalString();
    }

    public function removeStock($productId)
    {
        $this->validate(); // Memvalidasi input berdasarkan rules

        $product = Product::find($productId);

        if (!$product) {
            session()->flash('error', 'Produk tidak ditemukan.');
            return;
        }

        $quantity = $this->exitQuantities[$productId] ?? 0;

        if ($quantity <= 0) {
            session()->flash('error', 'Masukkan jumlah yang valid.');
            return;
        }

        if ($quantity > $product->stock) {
            session()->flash('error', 'Stok tidak mencukupi.');
            return;
        }

        // PENTING: Jika exits_date kosong (misalnya, pengguna menghapusnya),
        // maka setel kembali ke TANGGAL DAN WAKTU SAAT INI.
        if (empty($this->exits_date)) {
            $this->exits_date = Carbon::now()->toDateTimeLocalString();
        }

        try {
            $product->decrement('stock', $quantity);

            StockExit::create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'exits_date' => $this->exits_date, // Nilai ini akan selalu memiliki jam yang benar
                'price' => $product->price,
            ]);

            session()->flash('message', 'Stok berhasil dikurangi.');
            $this->exitQuantities[$productId] = 0;
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
                return $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->get();

        return view('livewire.exits-table', [
            'products' => $products,
        ]);
    }
}
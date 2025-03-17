<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StockEntri;
use App\Models\StockExit;
use App\Models\Product;

class ReportAll extends Component
{
    public $stockEntri;
    public $stockExit;
    public $totalStock;
    public $totalSales;
    public $totalProducts;
    public $totalPriceProducts;

    public function mount()
    {
        // Menghitung total stok masuk dan keluar
        $this->stockEntri = StockEntri::sum('quantity');
        $this->stockExit = StockExit::sum('quantity');

        // Menghitung total jumlah produk yang tersedia
        $this->totalStock = Product::sum('stock'); 
        $this->totalProducts = Product::count(); // Menghitung jumlah produk


        // Menghitung total penjualan (jumlah exit x harga masing-masing)
        $this->totalSales = StockExit::sum(\DB::raw('quantity * price'));
        $this->totalPriceProducts = StockEntri::sum(\DB::raw('quantity * price'));
    }

    public function render()
    {
        return view('livewire.report-all');
    }
}

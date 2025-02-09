<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\StockEntri;
use App\Models\StockExit;

class ReportHeader extends Component
{
    public function render()
    {
        return view('livewire.report-header', [
            'totalProducts' => Product::count(),
            'totalEntries' => StockEntri::count(),
            'totalExits' => StockExit::count(),
        ]);
    }
}

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

<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\StockEntri;
use App\Models\StockExit;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;

class ReportTable extends Component
{
    public $startDate;
    public $endDate;
    public $data;

    protected $queryString = ['startDate', 'endDate', 'data']; // Add 'data' to query string

    public function render()
    {
        $startDate = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : null;
        $endDate = $this->endDate ? Carbon::parse($this->endDate)->endOfDay() : null;

        $entries = collect(); // Initialize as empty collection
        $exits = collect(); // Initialize as empty collection

        // Only fetch entries if 'data' is 'Entri' or empty (for all data)
        if ($this->data == 'Entri' || empty($this->data)) {
            $entries = StockEntri::with('product')
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->get()
                ->map(function ($entry) {
                    $entry->type = 'Masuk';
                    return $entry;
                });
        }

        if ($this->data == 'Exit' || empty($this->data)) {
            $exits = StockExit::with('product')
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    // Use 'exits_date' for the date range filtering for StockExit
                    return $query->whereBetween('exits_date', [$startDate, $endDate]);
                })
                ->get()
                ->map(function ($exit) {
                    $exit->type = 'Keluar';
                    $exit->created_at = Carbon::parse($exit->exits_date);
                    return $exit;
                });
        }

        $transactions = $entries->concat($exits)->sortByDesc('created_at')->values();

        return view('livewire.report-table', [
            'transactions' => $transactions
        ]);
    }
}
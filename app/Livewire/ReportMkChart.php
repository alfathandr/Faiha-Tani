<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\StockEntri;
use App\Models\StockExit;
use Carbon\Carbon;

class ReportMkChart extends Component
{
    public $months = [];
    public $entriesData = [];
    public $exitsData = [];

    public function mount()
    {
        // Ambil data dari 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $this->months[] = Carbon::now()->subMonths($i)->format('M Y');

            $this->entriesData[] = StockEntri::whereYear('created_at', substr($month, 0, 4))
                ->whereMonth('created_at', substr($month, 5, 2))
                ->count();

            $this->exitsData[] = StockExit::whereYear('created_at', substr($month, 0, 4))
                ->whereMonth('created_at', substr($month, 5, 2))
                ->count();
        }
    }

    public function render()
    {
        return view('livewire.report-mk-chart');
    }
}

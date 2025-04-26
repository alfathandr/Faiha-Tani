<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockEntri; // Pastikan baris ini ada dan benar
use App\Models\StockExit;  // Pastikan baris ini ada dan benar
use Carbon\Carbon;
use Illuminate\View\View;

class ReportController extends Controller
{
    
    public function index(Request $request) 
    {

        return view('pages.reports');
    }

    public function show(string $type): View
    {
        $stockEntries = StockEntri::with('product');
        $stockExits = StockExit::with('product');
        $products = Product::all();
        $title = '';

        if ($type === 'all') {
            $stockEntries = $stockEntries->get();
            $stockExits = $stockExits->get();
            $title = 'Laporan Semua Data';
        } elseif ($type === 'monthly') {
            $now = Carbon::now();
            $startOfMonth = $now->startOfMonth()->toDateString();
            $endOfMonth = $now->endOfMonth()->toDateString();

            $stockEntries = $stockEntries->whereBetween('created_at', [$startOfMonth, $endOfMonth])->get();
            $stockExits = $stockExits->whereBetween('created_at', [$startOfMonth, $endOfMonth])->get();
            $products = null; // Tidak menampilkan data produk di laporan bulanan (sesuai permintaan awal)
            $title = 'Laporan Bulan Ini';
        } else {
            // Handle jika parameter type tidak valid
            abort(404);
        }

        return view('report.single', compact('stockEntries', 'stockExits', 'products', 'title'));
    }
}

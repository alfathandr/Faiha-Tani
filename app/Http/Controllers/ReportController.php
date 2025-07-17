<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockEntri;
use App\Models\StockExit;
use Carbon\Carbon;
use Illuminate\Contracts\View\View; // Preferred for type hinting views

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        // This method simply shows the reports dashboard or main page
        return view('pages.reports');
    }

    public function show(string $type): View
    {
        // Initialize variables
        $stockEntries = collect();
        $stockExits = collect();
        $products = collect();
        $title = '';
        $reportGeneratedAt = Carbon::now(); // **NEW**: Capture the report generation time

        if ($type === 'all') {
            // --- Laporan Semua Data ---
            $stockEntries = StockEntri::with('product')->get();
            $stockExits = StockExit::with('product')->get();
            $title = 'Laporan Semua Data';

            // Kalkulasi keuntungan untuk semua produk berdasarkan seluruh riwayat
            $products = Product::with('stockExits')->get()->map(function ($product) {
                $totalQuantitySold = $product->stockExits->sum('quantity');
                $totalRevenue = $product->stockExits->map(function ($exit) {
                    return $exit->quantity * $exit->price;
                })->sum();
                $totalCogs = $totalQuantitySold * $product->cost_price;
                $profit = $totalRevenue - $totalCogs;

                $product->total_quantity_sold = $totalQuantitySold;
                $product->total_revenue = $totalRevenue;
                $product->total_cogs = $totalCogs;
                $product->profit = $profit;

                return $product;
            });

        } elseif ($type === 'monthly') {
            // --- Laporan Bulanan ---
            $now = Carbon::now();
            $startOfMonth = $now->copy()->startOfMonth();
            $endOfMonth = $now->copy()->endOfMonth();
            $title = 'Laporan Bulan Ini (' . $now->format('F Y') . ')';

            // 1. Ambil transaksi masuk HANYA untuk bulan ini (menggunakan created_at)
            $stockEntries = StockEntri::with('product')
                                     ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                                     ->get();

            // 2. Ambil transaksi keluar HANYA untuk bulan ini (menggunakan exits_date)
            $stockExits = StockExit::with('product')
                                   ->whereBetween('exits_date', [$startOfMonth, $endOfMonth]) // **FIXED HERE**
                                   ->get();

            // 3. Ambil produk yang memiliki transaksi keluar di bulan ini,
            //    dan relasi 'stockExits' yang di-load HANYA dari bulan ini.
            $products = Product::with(['stockExits' => function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('exits_date', [$startOfMonth, $endOfMonth]); // **FIXED HERE**
            }])
            ->whereHas('stockExits', function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('exits_date', [$startOfMonth, $endOfMonth]); // **FIXED HERE**
            })
            ->get()
            ->map(function ($product) {
                // Logika kalkulasi ini sekarang berjalan pada data 'stockExits' yang sudah terfilter per bulan.
                $totalQuantitySold = $product->stockExits->sum('quantity');
                $totalRevenue = $product->stockExits->map(function ($exit) {
                    return $exit->quantity * $exit->price;
                })->sum();
                $totalCogs = $totalQuantitySold * $product->cost_price;
                $profit = $totalRevenue - $totalCogs;

                $product->total_quantity_sold = $totalQuantitySold;
                $product->total_revenue = $totalRevenue;
                $product->total_cogs = $totalCogs;
                $product->profit = $profit;

                return $product;
            });

        } else {
            abort(404, 'Jenis laporan tidak valid.');
        }

        // Pass the report generation time to the view
        return view('report.single', compact('stockEntries', 'stockExits', 'products', 'title', 'reportGeneratedAt'));
    }

    public function entries(): View
    {
        $stockEntries = StockEntri::with('product')->get();
        $title = 'Laporan Semua Data Masuk';
        $reportGeneratedAt = Carbon::now(); // **NEW**: Capture the report generation time

        return view('report.entries', compact('stockEntries', 'title', 'reportGeneratedAt'));
    }

    public function exits(): View
    {
        $stockExits = StockExit::with('product')->get();
        $title = 'Laporan Semua Data Keluar';
        $reportGeneratedAt = Carbon::now(); // **NEW**: Capture the report generation time

        return view('report.exits', compact('stockExits', 'title', 'reportGeneratedAt'));
    }
}
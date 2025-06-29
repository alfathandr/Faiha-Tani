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

        if ($type === 'all') {
            // --- Laporan Semua Data (Sudah Benar) ---
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
            // --- Laporan Bulanan (BAGIAN YANG DIPERBAIKI) ---
            $now = Carbon::now();
            $startOfMonth = $now->copy()->startOfMonth();
            $endOfMonth = $now->copy()->endOfMonth();
            $title = 'Laporan Bulan Ini (' . $now->format('F Y') . ')';

            // 1. Ambil transaksi masuk & keluar HANYA untuk bulan ini.
            $stockEntries = StockEntri::with('product')
                                        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                                        ->get();
            $stockExits = StockExit::with('product')
                                        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                                        ->get();

            // 2. Ambil produk, tetapi relasi 'stockExits' yang di-load HANYA dari bulan ini.
            //    Ini adalah perbaikan kunci untuk logika yang akurat.
            $products = Product::with(['stockExits' => function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            }])
            ->whereHas('stockExits', function ($query) use ($startOfMonth, $endOfMonth) {
                 $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            }) // Hanya ambil produk yang memiliki transaksi keluar di bulan ini
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
            }); // 3. Sintaks penutup 'map()' yang hilang sudah diperbaiki.

        } else {
            abort(404, 'Jenis laporan tidak valid.');
        }

        return view('report.single', compact('stockEntries', 'stockExits', 'products', 'title'));
    }
    // public function show(string $type): View
    // {
    //     // Initialize variables to null or empty collections for consistency
    //     $stockEntries = collect();
    //     $stockExits = collect();
    //     $products = collect(); // Initialize as empty collection

    //     $title = '';

    //     if ($type === 'all') {
    //         $stockEntries = StockEntri::with('product')->get();
    //         $stockExits = StockExit::with('product')->get();
    //         $products = Product::all(); // Fetch all products for 'all' report
    //         $title = 'Laporan Semua Data';
    //     } elseif ($type === 'monthly') {
    //         $now = Carbon::now();
    //         // Use copy() to avoid modifying the original Carbon instance
    //         $startOfMonth = $now->copy()->startOfMonth()->toDateString();
    //         $endOfMonth = $now->copy()->endOfMonth()->toDateString();

    //         $stockEntries = StockEntri::with('product')
    //                                 ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
    //                                 ->get();
    //         $stockExits = StockExit::with('product')
    //                               ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
    //                               ->get();
    //         $title = 'Laporan Bulan Ini (' . $now->format('F Y') . ')';
    //         // $products remains an empty collection, as it's not needed for monthly report
    //     } else {
    //         // Abort if the provided type is not valid
    //         abort(404, 'Jenis laporan tidak valid.');
    //     }

    //     return view('report.single', compact('stockEntries', 'stockExits', 'products', 'title'));
    // }

    public function entries(): View // Removed 'string $type' if not used in route
    {
        $stockEntries = StockEntri::with('product')->get();
        $title = 'Laporan Semua Data Masuk';

        // Only pass variables that are actually used in 'report.entries' view
        // Assuming 'products' is not used here, if it is, fetch it.
        return view('report.entries', compact('stockEntries', 'title'));
    }

    public function exits(): View // Removed 'string $type' if not used in route
    {
        $stockExits = StockExit::with('product')->get();
        $title = 'Laporan Semua Data Keluar';

        // Only pass variables that are actually used in 'report.exits' view
        // Assuming 'products' is not used here, if it is, fetch it.
        return view('report.exits', compact('stockExits', 'title'));
    }
}
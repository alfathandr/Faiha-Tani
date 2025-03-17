<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class LandingController extends Controller
{
    public function index(Request $request) 
    {
        // Ambil semua produk dari database
        $products = Product::all();

        return view('landing.welcome', compact('products'));
    }
}

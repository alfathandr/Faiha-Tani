<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExitsController extends Controller
{
    public function index(Request $request) 
    {

        return view('pages.exits');
    }
}

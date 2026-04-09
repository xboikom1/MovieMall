<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        return view('cart');
    }
}


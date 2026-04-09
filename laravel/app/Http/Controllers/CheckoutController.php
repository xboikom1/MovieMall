<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class CheckoutController extends Controller
{
    public function index(): View
    {
        return view('checkout');
    }
}


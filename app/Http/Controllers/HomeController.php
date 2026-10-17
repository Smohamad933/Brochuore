<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Marketing / landing page for the platform.
     */
    public function index(): View
    {
        return view('home');
    }
}

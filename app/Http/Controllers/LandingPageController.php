<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        if (auth()->check() && auth()->user()->can('view-dashboard')) {
            return redirect()->route('dashboard');
        }

        return view('landing.index');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        Inertia::setRootView('root');
        // Build the Inertia response…
        return Inertia::render('Home');

    }
}

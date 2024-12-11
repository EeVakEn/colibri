<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function videos(): Response
    {
        return Inertia::render('Videos', ['title' => 'Colibri | Videos']);
    }

    public function articles(): Response
    {
        return Inertia::render('Articles', ['title' => 'Colibri | Articles']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class CabinetController extends Controller
{
    public function account(): InertiaResponse
    {
        return Inertia::render('Cabinet/Account');
    }
    public function update(Request $request)
    {

    }
}

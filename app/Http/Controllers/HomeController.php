<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function videos(): Response
    {
        return Inertia::render('Home', [
            'title' => 'Videos',
            'user' => auth()->user() ?? null,
            'type' => Content::TYPE_VIDEO,
        ]);
    }


    public function articles(): Response
    {
        return Inertia::render('Home', [
            'title' => 'Articles',
            'user' => auth()->user() ?? null,
            'type' => Content::TYPE_ARTICLE,
        ]);
    }

    public function search(): Response
    {
        return Inertia::render('Search', [
            'title' => 'Search',
            'user' => auth()->user() ?? null,
            'query' => request()->q,
        ]);
    }
}


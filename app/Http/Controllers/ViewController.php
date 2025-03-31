<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Content;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function view(Content $content, Request $request)
    {
        $validatedData = $request->validate([
            'time' => 'nullable|integer|min:0',
        ]);

        $content->views()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['time' => $validatedData['time'] ?? 0]
        );
        return response()->json(['message' => 'View recorded']);
    }
}

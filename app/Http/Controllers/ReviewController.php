<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Content;

class ReviewController extends Controller
{
    public function store(Content $content, ReviewRequest $request)
    {
        $userId = \Auth::id();

        $validatedData = $request->validated();
        $content->reviews()->updateOrCreate(
            ['user_id' => $userId],
            $validatedData
        );
        return response()->json(['success' => 'Review published successfully.', 'reviews'=>$content->reviews->load('user')]);
    }
}

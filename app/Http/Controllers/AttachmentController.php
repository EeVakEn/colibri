<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $attachment = Attachment::upload($request->file('image'));

        return response()->json([
            'url' => $attachment->url,
            'attachment_id' => $attachment->id,
        ]);
    }
}

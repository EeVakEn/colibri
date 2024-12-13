<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class CabinetController extends Controller
{
    public function account(): InertiaResponse
    {
        return Inertia::render('Cabinet/Account')->with(['message'=>'hi']);
    }

    public function uploadAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => 'required|image|max:512',
        ]);

        $user = auth()->user();

        if ($user->avatar) {
            Storage::disk('public')->delete(str_replace('/storage', '', $user->avatar));
        }
        $ext = $request->file('avatar')->getClientOriginalExtension();
        $time = time();
        $path = "/avatar/$time.$ext";
        Storage::disk('public')->put($path, $request->file('avatar')->getContent());
        $user->update(['avatar' => '/storage' . $path]);

        return response()->json(['path' => $path], 201);
    }

    public function update(AccountUpdateRequest $request): InertiaResponse
    {
        auth()->user()->update($request->validated());
        return Inertia::render('Cabinet/Account')->with(['message'=>'Successfully updated']);
    }
}

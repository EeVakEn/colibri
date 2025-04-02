<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountUpdateRequest;
use App\Services\Table\ContentTable;
use App\Services\Table\HistoryTable;
use App\Services\Table\SkillsTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class AccountController extends Controller
{
    public function __construct(
        protected readonly SkillsTable $skillsTable,
        protected readonly HistoryTable $historyTable,
    )
    {
    }

    public function account(Request $request): InertiaResponse
    {
        return Inertia::render('Account/Index', [
            'skillsTableMeta' => $this->skillsTable->getMeta(),
            'historyTableMeta' => $this->historyTable->getMeta(),
            'skill_max_score' => auth()->user()->skill_max_score,
            'csrf' => csrf_token(),
        ]);
    }

    public function skills(Request $request): JsonResponse
    {
        return response()->json(['data' => $this->skillsTable->getData($request)]);
    }

    public function history(Request $request): JsonResponse
    {
        return response()->json(['data' => $this->historyTable->getData($request)]);
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
        return Inertia::render('Account/Index')->with(['message' => 'Successfully updated']);
    }
}

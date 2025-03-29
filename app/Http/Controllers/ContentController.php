<?php

namespace App\Http\Controllers;

use App\Enum\ContentTypes;
use App\Http\Requests\SkillsActivateRequest;
use App\Http\Requests\StoreChannelRequest;
use App\Http\Requests\StoreContentRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\Models\Channel;
use App\Models\Content;
use App\Services\ContentService;
use App\Services\Table\ChannelTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ContentController extends Controller
{

    public function __construct(
        private readonly ContentService $contentService,
    )
    {
    }


    public function create()
    {
        return Inertia::render('Content/Create', [
            'types' => ContentTypes::toMap(),
            'channels' => auth()->user()->channels()->pluck('name', 'id'),
        ]);
    }

    public function store(StoreContentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $channel = Channel::findOrFail($request->input('channel_id'));
        $content = $channel->contents()->create($validated);
        $this->contentService->processContent($content);
        return redirect()->route('account.channels.index')->with('message', 'Content created.');
    }

    public function show(Content $content): InertiaResponse
    {
        $data = $content->load('channel')->append('similar');
        return Inertia::render('Content/Show', [
            'content' => $data,
        ]);
    }

    public function edit(Content $content): InertiaResponse
    {
        return Inertia::render('Content/Edit', [
            'content' => $content,
        ]);
    }

    public function update(StoreContentRequest $request, Content $content): RedirectResponse
    {
        $validated = $request->validated();
        $content->update($validated);
        return redirect()->route('account.channels.index')->with('message', 'Channel updated.');
    }

    public function destroy(Content $content): RedirectResponse
    {
        $content->delete();
        return redirect()->route('account.studio.index')->with('message', 'Content deleted.');
    }

    public function activateSkills(Content $content, SkillsActivateRequest $request): RedirectResponse
    {
        foreach ($request->input('skill_ids') as $skillId) {
            $content->skills()->updateExistingPivot($skillId, ['activated_at' => now()]);
        }
        return redirect()->back();
    }
}

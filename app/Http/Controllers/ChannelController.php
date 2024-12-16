<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\Models\Channel;
use App\Services\Table\ChannelTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ChannelController extends Controller
{
    public function __construct(
        protected readonly ChannelTable $channelTable,
    )
    {}

    public function index(Request $request): JsonResponse|InertiaResponse
    {
        if ($request->wantsJson()) {
            return response()->json(['data' => $this->channelTable->getData($request)]);
        }

        return Inertia::render('Account/Channels/Index', [
            'tableMeta' => $this->channelTable->getMeta(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Account/Channels/Create', [
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChannelRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        if ($request->hasFile('avatar')) {
            $ext = $request->file('avatar')->getClientOriginalExtension();
            $time = time();
            $path = "/channel/avatar/$time.$ext";
            Storage::disk('public')->put($path, $request->file('avatar')->getContent());
            $validated['avatar'] = '/storage'. $path;
        }
        auth()->user()->channels()->create($validated);
        return redirect()->route('account.channels.index')->with('message', 'Channel created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Channel $channel): InertiaResponse
    {
        return Inertia::render('Account/Channels/Show', [
            'channel' => $channel,
            'subsCount' => $channel->subscriptions()->count()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Channel $channel): InertiaResponse
    {
        return Inertia::render('Account/Channels/Edit', [
            'channel' => $channel,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreChannelRequest $request, Channel $channel): RedirectResponse
    {
        $validated = $request->validated();
        if ($request->hasFile('avatar')) {
            Storage::disk('public')->delete(str_replace( '/storage', '', $channel->avatar));
            $ext = $request->file('avatar')->getClientOriginalExtension();
            $time = time();
            $path = "/channel/avatar/$time.$ext";
            Storage::disk('public')->put($path, $request->file('avatar')->getContent());
            $validated['avatar'] = '/storage'. $path;
        }
        $channel->update($validated);
        return redirect()->route('account.channels.index')->with('message', 'Channel updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Channel $channel): RedirectResponse
    {
        $channel->delete();
        return redirect()->route('account.channels.index')->with('message', 'Channel deleted.');
    }
}

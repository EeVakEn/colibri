<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Content;
use App\Models\View;
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


    public function getViewsByChannel()
    {
        $user = auth()->user();
        $channels = $user->channels;

        $data = [];

        foreach ($channels as $channel) {
            $views = $channel->views()
                ->where('views.created_at', '>=', now()->subDays(15))
                ->select(
                    \DB::raw('DATE(views.created_at) as date'),
                    \DB::raw('COUNT(views.id) as count'),
                    'contents.channel_id as laravel_through_key'
                )
                ->groupBy('date', 'contents.channel_id')
                ->orderBy('date')
                ->get();
            $viewData = $views->map(function ($view) {
                return [
                    'x' => $view->date,
                    'y' => $view->count,
                ];
            });

            $data[] = [
                'label' => $channel->name,
                'data' => $viewData->toArray(),
            ];
        }

        return response()->json($data);
    }
}

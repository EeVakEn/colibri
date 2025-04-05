<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ApiController extends Controller
{

    const PER_PAGE = 10;

    public function content(Request $request, ?string $type = null): JsonResponse
    {
        $perPage = self::PER_PAGE;
        $user = auth()->user();
        $query = Content::with(['skills', 'views']);

        if ($type) {
            $query->where('type', $type);
        }
        if ($request->has('q') && $request->input('q') !== '') {
            $searchQuery = $request->input('q');
            $query = Content::with(['views', 'channel'])->whereIn('contents.id', Content::search($searchQuery)->get()->pluck('id')->toArray());
        } else {
            if ($user) {
                $userSkills = $user->skills->pluck('name')->toArray();

                $contents = $query->get()->filter(function ($content) use ($userSkills) {
                    return !empty(array_intersect($userSkills, $content->skills->pluck('name')->toArray()));
                })->sortByDesc(function ($content) use ($userSkills) {
                    return count(array_intersect($userSkills, $content->skills->pluck('name')->toArray()));
                });

                $page = $request->input('page', 1);
                $paginated = $contents->forPage($page, $perPage)->values();

                return response()->json([
                    'data' => $paginated->map(fn($content) => [
                        'id' => $content->id,
                        'title' => $content->title,
                        'channel' => $content->channel,
                        'type' => $content->type,
                        'views_count' => $content->views_count,
                        'preview' => $content->preview,
                        'skills' => $content->skills->pluck('name')->toArray(),
                    ]),
                    'current_page' => $page,
                    'last_page' => ceil($contents->count() / $perPage),
                ]);
            }
        }



        $paginated = $query
            ->withCount('views')
            ->orderByDesc('views_count')
            ->paginate($perPage);

        $mapped = $paginated->getCollection()->map(fn($content) => [
            'id' => $content->id,
            'title' => $content->title,
            'channel' => $content->channel,
            'type' => $content->type,
            'views_count' => $content->views_count,
            'preview' => $content->preview,
            'skills' => $content->skills->pluck('name')->toArray(),
        ]);

        return response()->json([
            'data' => $mapped,
            'current_page' => $paginated->currentPage(),
            'last_page' => $paginated->lastPage(),
        ]);
    }
}

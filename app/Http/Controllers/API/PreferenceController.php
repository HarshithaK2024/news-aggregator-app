<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Preference;
use App\Models\Article;
use Jenssegers\Mongodb\Eloquent\Builder;

/**
 * @OA\Tag(
 *     name="User Preferences",
 *     description="API Endpoints for managing user preferences and personalized feed"
 * )
 */
class PreferenceController extends Controller
{

    public function getPersonalizedFeed()
    {
        $preferences = auth()->user()->preferences;

        $query = Article::query();

        if (!empty($preferences->preferred_authors)) {
            $query->where(function ($q) use ($preferences) {
                foreach ($preferences->preferred_authors as $author) {
                    $q->orWhere('author', 'LIKE', '%' . $author . '%');
                }
            });
        }
        if (!empty($preferences->preferred_categories)) {
            $query->whereIn('category', $preferences->preferred_categories);
        }

        if (!empty($preferences->preferred_sources)) {
            $query->whereIn('source', $preferences->preferred_sources);
        }

        return response()->json($query->orderBy('published_at', 'desc')->paginate(20), 200);


        //     $query = Article::query();

        // // Filter by preferred authors
        // if (!empty($preferences->preferred_authors)) {
        //     $query->where(function (Builder $q) use ($preferences) {
        //         foreach ($preferences->preferred_authors as $author) {
        //             $q->orWhere('author', 'regex', new \MongoDB\BSON\Regex($author, 'i'));
        //         }
        //     });
        // }

        // // Filter by preferred categories
        // if (!empty($preferences->preferred_categories)) {
        //     $query->whereIn('category', $preferences->preferred_categories);
        // }

        // // Filter by preferred sources
        // if (!empty($preferences->preferred_sources)) {
        //     $query->whereIn('source', $preferences->preferred_sources);
        // }

        // // Order by published_at and paginate
        // $results = $query->orderBy('published_at', 'desc')->paginate(20);

        // return response()->json($results, 200);
    }
}

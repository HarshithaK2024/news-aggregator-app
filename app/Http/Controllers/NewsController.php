<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Services\MongoDBService;

class NewsController extends Controller
{
    protected $mongoDBService;

    public function __construct(MongoDBService $mongoDBService)
    {
        $this->mongoDBService = $mongoDBService;
    }

    public function fetchNews()
    {
        $client = new Client();
        $newsApiKey = env('NEWSAPI_KEY');
        $guardianApiKey = env('GUARDIAN_KEY');
        $nytimesApiKey = env('NYTIMES_KEY');

        $sources = [
            'NewsAPI' => 'https://newsapi.org/v2/top-headlines?country=us&apiKey='.$newsApiKey,
            'TheGuardian' => 'https://content.guardianapis.com/search?api-key='. $guardianApiKey,
            'NYTimes' => 'https://api.nytimes.com/svc/topstories/v2/home.json?api-key=' . $nytimesApiKey
        ];

        foreach ($sources as $source => $url) {
            $response = $client->get($url);
            $articles = json_decode($response->getBody()->getContents(), true);

            if ($source === 'NewsAPI') {
                $articles = $articles['articles'];
            } elseif ($source === 'TheGuardian') {
                $articles = $articles['response']['results'];
            } elseif ($source === 'NYTimes') {
                $articles = $articles['results'];
            }

            foreach ($articles as $article) {
                $normalizedArticle = $this->normalizeArticle($source, $article);
                // $this->mongoDBService->getCollection('articles')->insertOne($normalizedArticle);

                Article::create($normalizedArticle);
            }
        }

        return response()->json(['message' => 'News fetched and stored successfully'], 200);
    }

    private function normalizeArticle($source, $article)
    {
        switch ($source) {
            case 'NewsAPI':
                return [
                    'source' => $source,
                    'title' => $article['title'] ?? '',
                    'description' => $article['description'] ?? '',
                    'content' => $article['content'] ?? '',
                    'author' => $article['author'] ?? '',
                    'url' => $article['url'] ?? '',
                    'image_url' => $article['urlToImage'] ?? '',
                    'category' => $article['category'] ?? '',
                    'published_at' => $article['publishedAt'] ?? now()
                ];
            case 'TheGuardian':
                return [
                    'source' => $source,
                    'title' => $article['webTitle'] ?? '',
                    'description' => $article['fields']['trailText'] ?? '',
                    'content' => $article['fields']['bodyText'] ?? '',
                    'author' => $article['fields']['byline'] ?? '',
                    'url' => $article['webUrl'] ?? '',
                    'image_url' => $article['fields']['thumbnail'] ?? '',
                    'category' => $article['sectionName'] ?? '',
                    'published_at' => $article['webPublicationDate'] ?? now()
                ];
            case 'NYTimes':
                return [
                    'source' => $source,
                    'title' => $article['title'] ?? '',
                    'description' => $article['abstract'] ?? '',
                    'content' => $article['body'] ?? '',
                    'author' => $article['byline'] ?? '',
                    'url' => $article['url'] ?? '',
                    'image_url' => $article['multimedia'][0]['url'] ?? '',
                    'category' => $article['section'] ?? '',
                    'published_at' => $article['published_date'] ?? now()
                ];
            default:
                return [];
        }
    }
}

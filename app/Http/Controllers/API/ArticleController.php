<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index(){
        $articles = Article::where('_id', 'exists','true')->get();


    }

    public function show(Article $article)
    {
        return response()->json($article,200);
    }
}
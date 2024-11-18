<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category',
        'keywords',
        'sources',
        'language',
        'categories',
        'authors'
    ];  

    protected $casts = [
        'keywords' => 'array',
        'sources' => 'array',
        'categories' => 'array',
        'authors' => 'array'
    ];  

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }   

    // public function articles()
    // {
    //     return $this->hasMany(Article::class);
    // }

    public function getFeed()
    {
        $articles = Article::query();

        if ($this->category) {
            $articles->where('category', $this->category);
        }

        if ($this->keywords) {
            $articles->where(function ($query) {
                foreach ($this->keywords as $keyword) {
                    $query->where('title', 'like', "%$keyword%")
                        ->orWhere('description', 'like', "%$keyword%");

                }
            }); 
        }
    }

}
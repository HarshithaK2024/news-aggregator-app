<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $collection = 'articles';

    protected $fillable = [
        'title',
        'description',
        'content',
        'source',
        'author',
        'url',
        'image_url',
        'category',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime'
    ];

    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'author' => $this->author,
            'category' => $this->category,
        ];
    }
}

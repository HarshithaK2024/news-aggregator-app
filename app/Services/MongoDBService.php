<?php

namespace App\Services;

use MongoDB\Client;
use Log;

class MongoDBService
{
    protected $client;
    protected $database;

    public function __construct()
    {   
        log::info(env('DB_DATABASE'));
        
        $this->client = new Client(env('MONGODB_URI'));
        $this->database = $this->client->selectDatabase(env('DB_DATABASE'));
    }

    public function getCollection($collection)
    {
        return $this->database->selectCollection($collection);
    }
}
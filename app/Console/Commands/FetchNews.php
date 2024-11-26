<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\NewsController;
use App\Services\MongoDBService;

class FetchNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:news';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch news from various sources and store in MongoDB';

    protected $mongoDBService;

    public function __construct(MongoDBService $mongoDBService)
    {
        parent::__construct();
        $this->mongoDBService = $mongoDBService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $newsController = new NewsController($this->mongoDBService);
        $newsController->fetchNews();
        $this->info('News fetched and stored successfully.');
    }
}

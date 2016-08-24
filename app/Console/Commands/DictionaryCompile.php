<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;

class DictionaryCompile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dictionary:compile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the dictionary table from source dictionary files.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $startTime = microtime(true);
        Artisan::call('db:seed', [
            '--class' => 'DictionarySeeder'
        ]);
        $stopTime = microtime(true);
        echo "Generated dictionary in: " . ($stopTime - $startTime) . "seconds \r\n";
    }
}

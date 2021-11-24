<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\LineMessengerController;

class TodayWorker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:today_worker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command today_worker';

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
     * @return int
     */
    public function handle()
    {
        LineMessengerController::today_worker();
    }
}

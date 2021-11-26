<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\LineMessengerController;

class RemindReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:remind_report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command remind_report';

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
        LineMessengerController::remind_report();
    }
}

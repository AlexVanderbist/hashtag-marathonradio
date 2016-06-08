<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Tweet;
use Carbon\Carbon;
use DB;

class UpdateDjColumnToSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tweets:updatedjs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the DJ column to match the schedule';

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
     * @return mixed
     */
    public function handle()
    {
		foreach (config('schedule') as $key => $schedule) {
			Tweet::where('tweeted_at_datetime', '>=', $schedule['start'])
					->where('tweeted_at_datetime', '<=',$schedule['stop'])
					->update([
						'dj' => $schedule['id']
					]);
			echo "Updated 1 schedule \n\r";
		}
    }
}

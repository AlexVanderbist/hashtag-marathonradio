<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\FixTweetDates::class,
	    Commands\GenerateWordOccurences::class,
	    Commands\LoadTweetsToDb::class,
	    Commands\UpdateDjColumnToSchedule::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
		$schedule->command('tweets:generateoccurences')->hourly();

		$schedule->command('tweets:load')->everyMinute();
    }
}

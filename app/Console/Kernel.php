<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Twitter;
use App\Tweet;
use App\TweetsPerSchedule;
use DB;
use Carbon\Carbon;

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
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
		$schedule->command('tweets:generateoccurences')->everyTenMinutes();

		$schedule->call(function () {

			$max_id = null;
			$reachedOldTweets = false;
			$highestTweetId = DB::table('tweets')->max('tweet_id');
			$highestTweetId = ($highestTweetId == null ? 0 : $highestTweetId);
			$numTweetsPostedThisSchedule = 0;

			do {

				// fetch tweets
				$tweets = Twitter::getSearch(['q' => '#marathonradio', 'count' => 100, 'max_id' => $max_id, 'since_id' => 739003964088291328]);

				echo "loaded new tweets<br/>";
				// loop through tweets
				foreach ($tweets->statuses as $tweet) {

					echo $tweet->id;
					// Check if we've reached old tweets
					if($tweet->id <= $highestTweetId) {
						$reachedOldTweets = true;
						echo "reached old tweets<br/>";
						break; // jump out of foreach
					}


					// If it's a new tweet, add it
					$newTweet = Tweet::firstOrNew([
						'tweet_id' => $tweet->id
					]);

					// Count new tweet this schedule
					$numTweetsPostedThisSchedule++;
					$formattedTweetedAt = Carbon::parse($tweet->created_at);
					$formattedTweetedAt->setTimezone('Europe/Brussels');
					$newTweet->fill([
						'user_id' => $tweet->user->id,
						'username' => $tweet->user->screen_name,
						'full_name' => $tweet->user->name,
						'tweet' => $tweet->text,
						'tweeted_at' => $formattedTweetedAt,
						'image' => $tweet->user->profile_image_url
					])->save();

					echo "saved tweet<br/>";

					// set highest tweet for next query
					$max_id = $tweet->id;
				}
				echo "end<br/>";
			} while (!empty($tweets->statuses) && !$reachedOldTweets);

			// loading tweets done, now save the scheulde tweet count to db
			TweetsPerSchedule::create(['num_new_tweets' => $numTweetsPostedThisSchedule]);


        })->everyMinute();
    }
}

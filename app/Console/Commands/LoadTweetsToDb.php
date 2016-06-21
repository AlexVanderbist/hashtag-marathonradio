<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Twitter;
use App\Tweet;
use App\TweetsPerSchedule;
use DB;
use Carbon\Carbon;

class LoadTweetsToDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tweets:load';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load tweets from twitter into db.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

	public function getCurrentDj() {
		date_default_timezone_set('Europe/Brussels');
		$now = strtotime('now');

		foreach (config('schedule') as $key => $schedule) {

			$start = Carbon::parse($schedule['start'])->timestamp;
			$stop  = Carbon::parse($schedule['stop'] )->timestamp;

			//echo DateTime::createFromFormat('Y-m-d G:i:s', '2016-06-09 08:00:00');
			if($now >= $start && $now < $stop) {
				return $schedule['id'];
			}
		}
		return 0;
	}

    /**
     * Execute the console command.
     *
     * @return mixed
     */
 	public function handle() {
		$max_id = null;
		$reachedOldTweets = false;
		$highestTweetId = DB::table('tweets')->max('tweet_id');
		$highestTweetId = ($highestTweetId == null ? 0 : $highestTweetId);
		$numTweetsPostedThisSchedule = 0;

		$dj = $this->getCurrentDj();
		echo $dj . "\n\r";

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
					'tweeted_at_datetime' => $formattedTweetedAt,
					'image' => $tweet->user->profile_image_url,
					'dj' => $dj
				])->save();

				echo "saved tweet<br/>";

				// set highest tweet for next query
				$max_id = $tweet->id;
			}
			echo "end<br/>";
		} while (!empty($tweets->statuses) && !$reachedOldTweets);

		// loading tweets done, now save the scheulde tweet count to db
		TweetsPerSchedule::create(['num_new_tweets' => $numTweetsPostedThisSchedule]);
	}
}

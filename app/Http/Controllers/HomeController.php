<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Twitter;
use App\Tweet;
use DB;

class HomeController extends Controller
{
    public function index() {

		$max_id = null;
		$reachedOldTweets = false;
		$highestTweetId = DB::table('tweets')->max('tweet_id');

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
				$newTweet->fill([
					'user_id' => $tweet->user->id,
					'username' => $tweet->user->screen_name,
					'full_name' => $tweet->user->name,
					'tweet' => $tweet->text,
					'tweeted_at' => $tweet->created_at
				])->save();
				
				echo "saved tweet<br/>";

				// set highest tweet for next query
				$max_id = $tweet->id;
			}
			echo "end<br/>";
		} while (!empty($tweets->statuses) && !$reachedOldTweets);



		return view('index', compact('hashtag'));
	}

	private function getTweetsWithTag($tag, $max_id = null){

	}
}

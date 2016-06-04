<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Twitter;
use App\Tweet;

class HomeController extends Controller
{
    public function index() {

		$max_id = null;
		$reachedOldTweets = false;

		do {
			$tweets = Twitter::getSearch(['q' => '#marathonradio', 'count' => 100, 'max_id' => $max_id, 'since_id' => 739003964088291328]);
			//dd($tweets->statuses);

			// Check once every while loop if we've already got these tweets
			$oldTweet = Tweet::where('tweet_id', '>=', $tweets->statuses[0]->id)->get();
			if($oldTweet->count()) {
				$reachedOldTweets = true;
			}


			foreach ($tweets->statuses as $tweet) {
				Tweet::firstOrCreate([
					'tweet_id' => $tweet->id,
					'user_id' => $tweet->user->id,
					'username' => $tweet->user->screen_name,
					'full_name' => $tweet->user->name,
					'tweet' => $tweet->text,
					'created_at' => $tweet->created_at
				]);
				$max_id = $tweet->id;
			}
			echo "saved<br/>";
		} while (!empty($tweets->statuses) && !$reachedOldTweets);



		return view('index', compact('hashtag'));
	}

	private function getTweetsWithTag($tag, $max_id = null){

	}
}

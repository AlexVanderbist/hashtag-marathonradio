<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Twitter;
use App\Tweet;
use App\TweetsPerSchedule;
use App\WordOccurence;
use DB;

class DataController extends Controller
{
	public function generateWords() {
		$allTweets = Tweet::all();
		$allWordsList = [];

		foreach ($allTweets as $key => $tweet) {
			$wordList = $this->extract_common_words($tweet->tweet,100);
			foreach ($wordList as $word => $count) {
				if(array_key_exists($word, $allWordsList)) $allWordsList[$word] += $count;
				else $allWordsList[$word] = $count;
			}
			echo "tweet";
		}

		foreach ($allWordsList as $word => $occurences) {
			WordOccurence::firstOrCreate([
				'word' => $word,
				'occurences' => $occurences
			]);
		}
	}

	function extract_common_words($string, $max_count = 5) {
		$string = preg_replace('/ss+/i', '', $string);
		$string = trim($string); // trim the string
		$string = preg_replace('/[^a-zA-Z -]/', '', $string); // only take alphabet characters, but keep the spaces and dashes too…
		$string = strtolower($string); // make it lowercase

		preg_match_all('/\b.*?\b/i', $string, $match_words);
		$match_words = $match_words[0];

		foreach ( $match_words as $key => $item ) {
			if ( $item == '' || in_array(strtolower($item), config('stopwords')) || strlen($item) <= 3 ) {
				unset($match_words[$key]);
			}
		}

		$word_count = str_word_count( implode(" ", $match_words) , 1);
		$frequency = array_count_values($word_count);
		arsort($frequency);

		//arsort($word_count_arr);
		$keywords = array_slice($frequency, 0, $max_count);
		return $keywords;
	}

    public function getData() {

		$usersWithMostHashtags = DB::table('tweets')
					                ->select(DB::raw('username'), DB::raw('count(*) as count'))
					                ->groupBy('user_id')
					                ->orderBy('count', 'desc')
					                ->take(300)
					                ->get();

		$totalUserCount = DB::table('tweets')->count(DB::raw('DISTINCT user_id'));

		$totalTweetCount = Tweet::count();

		$tpm = TweetsPerSchedule::orderBy('id','desc')->take(10)->get();
		//dd($tpm);
		$tps = $tpm->first()->num_new_tweets / 60;

		$schedule = [
			[
				'name' => 'julie',
				'start' => '2016-06-04 10:00:00',
				'stop' => '2016-06-04 18:00:00'
			],
			[
				'name' => 'tom',
				'start' => '2016-06-04 18:00:00',
				'stop' => '2016-06-05 08:00:00'
			],
			[
				'name' => 'peter',
				'start' => '2016-06-05 08:00:00',
				'stop' => '2016-06-05 18:00:00'
			],
			[
				'name' => 'julie',
				'start' => '2016-06-05 18:00:00',
				'stop' => '2016-06-06 08:00:00'
			],
			[
				'name' => 'tom',
				'start' => '2016-06-06 08:00:00',
				'stop' => '2016-06-06 18:00:00'
			]
		];

		$tweetsPerPerson = [
			'julie' => 0,
			'tom' => 0,
			'peter' => 0
		];

		foreach ($schedule as $key => $schedule) {
			$tweetsPerPerson[$schedule['name']] += Tweet::whereBetween('tweeted_at', [$schedule['start'], $schedule['stop']])->get()->count();
		}

		return response()->json(compact('tweetsPerPerson','usersWithMostHashtags', 'totalTweetCount', 'totalUserCount', 'tpm', 'tps'));
	}
}

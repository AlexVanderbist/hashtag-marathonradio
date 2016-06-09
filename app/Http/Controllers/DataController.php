<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Twitter;
use App\Tweet;
use App\TweetsPerSchedule;
use App\WordOccurence;
use DB;
use Carbon\Carbon;

class DataController extends Controller
{

	public function test() {
		$tenMinutesAgo = new Carbon('10 minutes ago');

		// DB::enableQueryLog();

		$allTweets = Tweet::where('tweeted_at_datetime', '>=', $tenMinutesAgo)->get();
		$allWordsList = [];
		$tweetCount = 0;

		// dd(DB::getQueryLog());

		foreach ($allTweets as $key => $tweet) {
			$wordList = $this->extract_common_words($tweet->tweet,100);
			foreach ($wordList as $word => $count) {
				if(array_key_exists($word, $allWordsList)) $allWordsList[$word] += $count;
				else $allWordsList[$word] = $count;
			}
			$tweetCount++;
		}

		dd($allWordsList);

		// foreach ($allWordsList as $word => $occurences) {
		// 	$oldWordOccurence = WordOccurence::firstOrCreate([
		// 		'word' => $word
		// 	]);
		// 	$oldWordOccurence->update(['occurences' => $occurences]);
		// }
		echo "\n\rDone: " + $tweetCount + " tweets processed!\n\r";
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




	////////////////////////////////////////////////////////////////////////////////////

	public function getTweetsPerMinute() {
		$schedules = TweetsPerSchedule::orderBy('id', 'desc')->take(12)->get();
		$tpm = $schedules->sum('num_new_tweets');
		return $tpm;
		return response()->json(compact('tpm'));
	}


    public function getData() {

		// DB::enableQueryLog();

		$usersWithMostHashtags = DB::table('tweets')
					                ->select('username', 'image', DB::raw('count(*) as count'))
									->where('tweet', 'not like', 'RT%')
					                ->groupBy('user_id')
					                ->orderBy('count', 'desc')
					                ->take(500)
					                ->get();

		$totalUserCount = DB::table('tweets')->count(DB::raw('DISTINCT user_id'));

		$totalTweetCount = DB::table('tweets')->count();

		//dd($tpm);
		$scheduleCounts = TweetsPerSchedule::orderBy('id', 'desc')->take(12)->get();
		$tpm = $scheduleCounts->sum('num_new_tweets');
		$tps = $tpm / 60;

		$wordOccurences = WordOccurence::orderBy('occurences', 'desc')->take(100)->get();

		$tweetsPerPerson = [
			'julie' => Tweet::where('dj', 1)->count(),
			'tom' => Tweet::where('dj', 2)->count(),
			'peter' => Tweet::where('dj', 3)->count()
		];

		$tpm = $this->getTweetsPerMinute();

		// dd(DB::getQueryLog());

		return response()->json(compact('tpm','wordOccurences','tweetsPerPerson','usersWithMostHashtags', 'totalTweetCount', 'totalUserCount', 'tpm', 'tps'));
	}
}

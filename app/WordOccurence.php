<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class WordOccurence extends Model
{
	public $timestamps = false;

	protected $fillable = [
		'word',
		'occurences'
	];

	static function countOccurences($timeDifference = false, $limit = 100) {

		$allWordsList = [];
		$tweetCount = 0;




		$chunkSize = 10000; // or whatever your memory allows
		$totalTweetCt = Tweet::count();

		$chunks = floor($totalTweetCt / $chunkSize);

		for ($chunk = 0; $chunk <= $chunks; $chunk++) {

		    $offset = $chunk * $chunkSize;

			if($timeDifference) {
				// return words from timeframe
				$timeAgo = new Carbon($timeDifference);
			    $tweets = Tweet::skip($offset)->take($chunkSize)->where('tweeted_at_datetime', '>=', $timeAgo)->get();
			} else {
				// all tweets
			    $tweets = Tweet::skip($offset)->take($chunkSize)->get();
			}

		    foreach($tweets as $tweet)
		    {
				$wordList = self::extract_common_words($tweet->tweet);
				foreach ($wordList as $word => $count) {
					if(array_key_exists($word, $allWordsList)) $allWordsList[$word] += $count;
					else $allWordsList[$word] = $count;
				}
				$tweetCount++;
		    }
		}

		arsort($allWordsList);
		$allWordsList = array_slice($allWordsList, 0, $limit);

		return $allWordsList;
   }

	static function extract_common_words($string) {
		//$string = preg_replace('/ss+/i', '', $string);
		$string = trim($string); // trim the string
		$string = preg_replace('/[^a-zA-Z\d -\_@#]/', '', $string); // only take alphabet characters, but keep the spaces and dashes too…
		$string = strtolower($string); // make it lowercase

		//preg_match_all('/\b.*?\b/i', $string, $match_words);
		$pattern = '/[ \n]/';
		$match_words = preg_split( $pattern, $string );

		// $match_words = $match_words[0];

		foreach ( $match_words as $key => $item ) {
			$item = trim($item);
			if (substr($item, 0, 4) === 'http' || $item == '' || in_array(strtolower($item), config('stopwords')) || strlen($item) <= 3 ) {
				//echo $item; // PAS DE STRLEN NOG AAN :(
				//if($item == "")echo '"' . $item . ','.$key.'"+';
				unset($match_words[$key]);
			}
		}


		// $word_count = str_word_count( implode(" ", $match_words) , 1);
		$frequency = array_count_values($match_words);
		//arsort($frequency)

		//dd($frequency);
		return $frequency;
	}
}

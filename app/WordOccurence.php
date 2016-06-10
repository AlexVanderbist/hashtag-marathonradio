<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WordOccurence extends Model
{
	public $timestamps = false;

	protected $fillable = [
		'word',
		'occurences'
	];

	static function countOccurences($timeDifference = false) {
		if($timeDifference) {
			// return words from timeframe
			$timeAgo = new Carbon($timeDifference);
			$tweets = Tweet::where('tweeted_at_datetime', '>=', $timeAgo)->get();
		} else {
			// all tweets
			$tweets = Tweet::get();
		}

		$allWordsList = [];
		$tweetCount = 0;

		foreach ($tweets as $key => $tweet) {
			$wordList = self::extract_common_words($tweet->tweet);
			foreach ($wordList as $word => $count) {
				if(array_key_exists($word, $allWordsList)) $allWordsList[$word] += $count;
				else $allWordsList[$word] = $count;
			}
			$tweetCount++;
		}

		arsort($allWordsList);

		return $allWordsList;
   }

	static function extract_common_words($string) {
		$string = preg_replace('/ss+/i', '', $string);
		$string = trim($string); // trim the string
		$string = preg_replace('/[^a-zA-Z\d -]/', '', $string); // only take alphabet characters, but keep the spaces and dashes too…
		$string = strtolower($string); // make it lowercase

		preg_match_all('/\b.*?\b/i', $string, $match_words);
		$match_words = $match_words[0];

		foreach ( $match_words as $key => $item ) {
			$item = trim($item);
			if ($item == '' || in_array(strtolower($item), config('stopwords')) || strlen($item) <= 3 ) {
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

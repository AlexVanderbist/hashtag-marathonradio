<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Tweet;
use Carbon\Carbon;
use DB;
use App\Tweet;

class FixTweetDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tweets:generateoccurences';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate word occurences for cache.';

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
	 public function handle() {
 		$allTweets = Tweet::all();
 		$allWordsList = [];
		$tweetCount = 0;

 		foreach ($allTweets as $key => $tweet) {
 			$wordList = $this->extract_common_words($tweet->tweet,100);
 			foreach ($wordList as $word => $count) {
 				if(array_key_exists($word, $allWordsList)) $allWordsList[$word] += $count;
 				else $allWordsList[$word] = $count;
 			}
			$tweetCount++;
 		}

 		foreach ($allWordsList as $word => $occurences) {
 			$oldWordOccurence = WordOccurence::firstOrCreate([
 				'word' => $word
 			]);
 			$oldWordOccurence->update(['occurences' => $occurences]);
 		}
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
}

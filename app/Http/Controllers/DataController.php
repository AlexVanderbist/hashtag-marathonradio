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
		dd(WordOccurence::countOccurences('30 minutes ago'));
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
		$wordOccurencesLastTenMinutesRaw = WordOccurence::countOccurences('20 minutes ago', 10);
		$wordOccurencesLastTenMinutes = [];
		foreach ($wordOccurencesLastTenMinutesRaw as $word => $count) {
			$wordOccurencesLastTenMinutes[] = [
				'word' => $word,
				'occurences' => $count
			];
		}

		//$winningTweet = DB::table('tweets')->orderBy('tweeted_at_datetime', 'asc')->skip(100000)->take(1)->first();
		$winningTweet = [
			'image' => "http://pbs.twimg.com/profile_images/732281049858867200/ZTmjDU41_normal.jpg",
			'tweet' => "Liefde voor #marathonradio #marathonradio #nothingbutlove  https://t.co/8llrk9O4wO",
			'username' => 'LeniSonck'
		];
		//$lastTweet = DB::table('tweets')->orderBy('tweeted_at_datetime', 'desc')->take(1)->first();
//		dd($lastTweet);

		$tweetsPerPerson = [
			'julie' => Tweet::where('dj', 1)->count(),
			'tom' => Tweet::where('dj', 2)->count(),
			'peter' => Tweet::where('dj', 3)->count()
		];

		$tpm = $this->getTweetsPerMinute();

		// dd(DB::getQueryLog());

		$forceRefresh = false;

		return response()->json(compact(
								'winningTweet',
								'forceRefresh',
								'lastTweet',
								'wordOccurencesLastTenMinutes',
								'tpm',
								'wordOccurences',
								'tweetsPerPerson',
								'usersWithMostHashtags',
								'totalTweetCount',
								'totalUserCount',
								'tpm',
								'tps'
							));
	}
}

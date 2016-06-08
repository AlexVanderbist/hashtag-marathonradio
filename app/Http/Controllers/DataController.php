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

	public function getTweetsPerMinute() {
		$schedules = TweetsPerSchedule::orderBy('id', 'desc')->take(12)->get();
		$tpm = $schedules->sum('num_new_tweets');
		return $tpm;
		return response()->json(compact('tpm'));
	}


    public function getData() {

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
			'julie' => '<small>tijdelijk onbeschikbaar</small>',
			'tom' => '<small>tijdelijk onbeschikbaar</small>',
			'peter' => '<small>tijdelijk onbeschikbaar</small>'
		];

		$tpm = $this->getTweetsPerMinute();

//DB::enableQueryLog();
		// foreach (config('schedule') as $key => $schedule) {
		// 	//$tweetsPerPerson[$schedule['name']] += Tweet::whereBetween('tweeted_at_datetime', [$schedule['start'], $schedule['stop']])->get()->count();
		// 	$tweetsPerPerson[$schedule['name']] += Tweet::where('tweeted_at_datetime', '>=', $schedule['start'])->where('tweeted_at_datetime', '<=',$schedule['stop'])->get()->count();
		// }
//dd(DB::getQueryLog());

		return response()->json(compact('tpm','wordOccurences','tweetsPerPerson','usersWithMostHashtags', 'totalTweetCount', 'totalUserCount', 'tpm', 'tps'));
	}
}

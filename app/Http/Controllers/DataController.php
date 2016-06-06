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
		return response()->json(compact('tpm'));
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

		//dd($tpm);
		$scheduleCounts = TweetsPerSchedule::orderBy('id', 'desc')->take(12)->get();
		$tpm = $scheduleCounts->sum('num_new_tweets');
		$tps = $tpm / 60;

		$wordOccurences = WordOccurence::orderBy('occurences', 'desc')->take(100)->get();

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

		return response()->json(compact('wordOccurences','tweetsPerPerson','usersWithMostHashtags', 'totalTweetCount', 'totalUserCount', 'tpm', 'tps'));
	}
}

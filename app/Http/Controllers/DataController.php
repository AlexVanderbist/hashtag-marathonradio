<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Twitter;
use App\Tweet;
use App\TweetsPerSchedule;
use DB;

class DataController extends Controller
{
    public function getData() {

		$usersWithMostHashtags = DB::table('tweets')
					                ->select(DB::raw('username'), DB::raw('count(*) as count'))
					                ->groupBy('user_id')
					                ->orderBy('count', 'desc')
					                ->take(300)
					                ->get();

		$totalUserCount = DB::table('tweets')->count(DB::raw('DISTINCT user_id'));

		$totalTweetCount = Tweet::count();

		$tpm = TweetsPerSchedule::all()->last()->num_new_tweets;
		$tps = $tpm / 60;

		return response()->json(compact('usersWithMostHashtags', 'totalTweetCount', 'totalUserCount', 'tpm', 'tps'));
	}
}

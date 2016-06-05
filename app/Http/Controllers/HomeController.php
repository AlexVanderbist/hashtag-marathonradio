<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Twitter;
use App\Tweet;
use DB;

class HomeController extends Controller
{
    public function index() {

		$usersWithMostHashtags = DB::table('tweets')
					                ->select(DB::raw('username'), DB::raw('count(*) as count'))
					                ->groupBy('user_id')
					                ->orderBy('count', 'desc')
					                ->take(300)
					                ->get();

		$totalUserCount = DB::table('tweets')->count(DB::raw('DISTINCT user_id'));

		$totalTweetCount = Tweet::count();

		$tweetsLastMinute = Tweet::where()

		return view('index', compact('usersWithMostHashtags', 'totalTweetCount', 'totalUserCount'));
	}

	private function getTweetsWithTag($tag, $max_id = null){

	}
}

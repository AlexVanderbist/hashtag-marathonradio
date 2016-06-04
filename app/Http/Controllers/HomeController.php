<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Twitter;
use App\Tweet;

class HomeController extends Controller
{
    public function index() {

		return view('index', compact('hashtag'));
	}

	private function getTweetsWithTag($tag, $max_id = null){

	}
}

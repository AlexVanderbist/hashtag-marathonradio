<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Tweet;
use Carbon\Carbon;
use DB;

class FixTweetDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tweets:fixdates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Converts tweets from twitter time to unix timestamp';

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
    public function handle()
    {
        //DB::table('tweets')->update(['tweeted_at' => Carbon::parse(DB::raw('tweeted_at'))]);
		$allTweets = Tweet::all();
		foreach($allTweets as $tweet) {
			$tweet->update(['tweeted_at' => Carbon::parse($tweet->tweeted_at)]);
		}
		echo "Done!";
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TweetsPerSchedule extends Model
{
	public $timestamps = false;

    protected $table = 'tweets_per_schedule';

	protected $fillable = [
		'num_new_tweets'
	];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TweetsPerSchedule extends Model
{
    protected $table = 'tweets_per_schedule';

	protected $fillable = [
		'num_tweets'
	];
}

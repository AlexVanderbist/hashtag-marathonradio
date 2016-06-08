<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
	public $timestamps = false;

	protected $fillable = [
		'tweet_id',
		'user_id',
		'username',
		'full_name',
		'tweet',
		'tweeted_at',
		'tweeted_at_datetime',
		'image'
	];
}

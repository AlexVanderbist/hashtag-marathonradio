<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
	protected $fillable = [
		'tweet_id',
		'user_id',
		'username',
		'full_name',
		'tweet',
		'tweeted_at',
		'image'
	];
}

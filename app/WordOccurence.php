<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WordOccurence extends Model
{
	public $timestamps = false;

	protected $fillable = [
		'word',
		'occurences'
	];
}

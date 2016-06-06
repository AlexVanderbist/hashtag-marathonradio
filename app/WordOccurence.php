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

	// protected $appends = ['weight', 'text'];
	//
	// public function getWeightAttribute(){
	//     return $this->attributes['occurences'];
	// }
	//
	// public function getTextAttribute(){
	//     return $this->attributes['word'];
	// }
}

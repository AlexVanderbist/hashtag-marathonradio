<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\WordOccurence;

class GenerateWordOccurences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tweets:generateoccurences';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate word occurences for cache.';

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
	 public function handle() {
 		$occurences = WordOccurence::all();

 		foreach ($occurences as $word => $count) {
 			$oldWordOccurence = WordOccurence::firstOrCreate([
 				'word' => $word
 			]);
 			$oldWordOccurence->update(['occurences' => $count]);
 		}
		echo "\n\r\n\rDone: " + count($occurences) + " tweets processed!\n\r\n\r";
 	}
}

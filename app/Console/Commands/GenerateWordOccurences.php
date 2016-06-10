<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\WordOccurence;
use DB;

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
 		$occurences = WordOccurence::countOccurences();
		$numOccurences = count($occurences);
		$addedCount = 0;

		DB::table('word_occurences')->truncate();

 		foreach ($occurences as $word => $count) {
			$addedCount ++;

			echo "> ".($addedCount/$numOccurences)*100 . "% done.\r";

 			WordOccurence::create([
 				'word' => $word,
				'occurences' => $count
 			]);
 		}
		echo "\n\nDone: " + $numOccurences + " tweets processed!\n";
 	}
}

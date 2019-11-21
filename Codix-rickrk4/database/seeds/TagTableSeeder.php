<?php

use Illuminate\Database\Seeder;
use App\Comic;
use App\Tag;
class TagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 10; $i++) { 
        	$tag = Tag::create(['name' => "tag n.$i"]);
        	for ($j=1; $j < 5; $j++) 
        		$tag->comics()->attach(App\Comic::findOrFail(($i+1) * $j));
        	
        }
    }
}

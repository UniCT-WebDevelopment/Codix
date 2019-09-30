<?php

use App\Comic;
use App\Series;
use Illuminate\Database\Seeder;

class SeriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 50; $i++) {
            $series = new Series;
            $series->name = "serietta-$i";
            $series->save();
            for ($j=0; $j < 10; $j++) {
                if($comic = Comic::find(($i*10)+$j+1))
                    $series->comics()->save($comic);
            }

        }

    }
}

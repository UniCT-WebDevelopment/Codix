<?php

use App\Collection;
use App\Comic;
use App\Directory;
use App\Series;
use Illuminate\Database\Seeder;

class CollectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $collection = Collection::create(['name' => 'collezione-1']);
        $collection->comics()->save(Comic::find(1));
        $collection->directories()->save(Directory::find(1));
        $collection->series()->save(Series::find(1));

    }
}

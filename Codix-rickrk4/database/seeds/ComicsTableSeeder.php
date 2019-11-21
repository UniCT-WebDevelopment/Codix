<?php

use App\Cover;
use App\Comic;
use App\Series;
use Illuminate\Database\Seeder;

class ComicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cover::create(['cover_path' => '../media/derpy.gif']);
        for ($i=0; $i < 50; $i++) {
            $comic = new App\Comic;
            $comic->file_path = $i;
            $comic->title = $i;
            $comic->cover_id = 1;
            $comic->save();
        }
    }
}

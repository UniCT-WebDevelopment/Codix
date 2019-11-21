<?php

use App\Comic;
use App\Directory;
use Illuminate\Database\Seeder;

class DirectoriesTableSeeder extends Seeder
{
    static function fillDir($dir){
        for ($i=0; $i < 10; $i++)
            if(rand(0,2))
                $dir->comics()->save(new Comic(['file_path' => microtime(), 'title' => "figlio $i di $dir->id", 'cover_id' => 1]));
            else
                $dir->directories()->save(new Directory(['path' => microtime(), 'name' => $i, 'cover_id' => 1]));

        foreach($dir->directories() as $dir)
            self::fillDir($dir);
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dir = Directory::create(['path' => '/', 'name' => '/']);
        self::fillDir($dir);
    }


}

<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        //$this->call(ComicsTableSeeder::class);
        //$this->call(DirectoriesTableSeeder::class);
        //$this->call(SeriesTableSeeder::class);
        //$this->call(CollectionsTableSeeder::class);
        //$this->call(TagTableSeeder::class);
    }
}

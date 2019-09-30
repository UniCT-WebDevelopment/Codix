<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Create Admin user
         */
        /*
        DB::table('users')->insert([
            'name' => config('settings.admin_credentials.username'),
            'password' => bcrypt(config('settings.admin_credentials.password')),
        ]);
        */

        User::create([
            'name' => config('settings.admin_credentials.username'),
            'password' => bcrypt(config('settings.admin_credentials.password'))
        ])->roles()->save(
            Role::create([
                'role' => 'admin'
            ])
        );

    }
}

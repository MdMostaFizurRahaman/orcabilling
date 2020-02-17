<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users =
    		[
    			['name' => 'Shahadat Hossen', 'email' => 'shobuj@bansberrysg.com', 'password' => Hash::make('asdfgh')],
        	];
        DB::table('users')->insert($users);
    }
}

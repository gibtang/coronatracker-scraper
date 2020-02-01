<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->delete();
	    \App\User::create(array(
	        'name'     => 'Gibson Tang',
	        'email'    => 'gibtang@gmail.com',
	        'password' => Hash::make('CoronaTracker0106'),
	    ));
    }
}
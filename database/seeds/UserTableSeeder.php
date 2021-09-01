<?php

use App\Models\User;
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
        //DB::table('t_users')->delete();

        User::create([
			'u_username' 		=> 'frbarquilla',
			'u_email'			=> 'frbarquilla@yahoo.com',
			'u_password' 		=> '9',
			'u_fname' 			=> 'Francisco',
			'u_mname' 			=> 'Reyes',
			'u_lname'			=> 'Barquilla III',
			'u_mobile'			=> '639103216547',
			'ug_id'				=> 0,
			'group_id'			=> 9,
			'u_position'		=> 'Sr. SRS',
			'u_picture'			=> 'upload/profile/1.png',
			'u_active'			=> 0,
			'u_administrator' 	=> 1
			'created_at' 		=> date('Y-m-d H:i:s')			
		]);
    }
}

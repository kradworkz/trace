<?php

use App\Models\UserRight;
use Illuminate\Database\Seeder;

class UserRightTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('t_user_rights')->delete();

		UserRight::create([
			'ur_name' 		=> 'Incoming Documents',
			'created_at' 	=> '2017-05-03 09:00:00'
		]);

		UserRight::create([
			'ur_name' 		=> 'Outgoing Documents',
			'created_at' 	=> '2017-05-03 09:00:00'
		]);

		UserRight::create([
			'ur_name' 		=> "RD's Calendar",
			'created_at' 	=> '2017-05-03 09:00:00'
		]);

		UserRight::create([
			'ur_name' 		=> 'Meetings',
			'created_at' 	=> '2017-05-03 09:00:00'
		]);

		UserRight::create([
			'ur_name' 		=> 'Events',
			'created_at' 	=> '2017-05-03 09:00:00'
		]);

		UserRight::create([
			'ur_name' 		=> 'Document Statistics',
			'created_at' 	=> '2017-05-03 09:00:00'
		]);

		UserRight::create([
			'ur_name' 		=> 'User Statistics',
			'created_at' 	=> '2017-05-03 09:00:00'
		]);

		UserRight::create([
			'ur_name' 		=> 'Unit Statistics',
			'created_at' 	=> '2017-05-03 09:00:00'
		]);

		UserRight::create([
			'ur_name' 		=> 'Company Information',
			'created_at' 	=> '2017-05-03 09:00:00'
		]);

		UserRight::create([
			'ur_name' 		=> 'Groups',
			'created_at' 	=> '2017-05-03 09:00:00'
		]);

		UserRight::create([
			'ur_name' 		=> 'Accounts',
			'created_at' 	=> '2017-05-03 09:00:00'
		]);

		UserRight::create([
			'ur_name' 		=> 'System Settings',
			'created_at' 	=> '2017-05-03 09:00:00'
		]);

		UserRight::create([
			'ur_name' 		=> 'User Groups',
			'created_at' 	=> '2017-05-03 09:00:00'
		]);
    }
}

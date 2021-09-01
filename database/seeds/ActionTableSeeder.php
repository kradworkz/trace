<?php

use App\Models\Action;
use Illuminate\Database\Seeder;

class ActionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('t_user_groups')->delete();

		Action::create([
			'a_action' 		=> 'Please RUSH',
			'a_number'		=> 1,
			'created_at' 	=> '2016-08-24 10:00:00'
		]);

		Action::create([
			'a_action' 		=> 'Please Attend',
			'a_number'		=> 2,
			'created_at' 	=> '2016-08-24 10:00:00'
		]);

		Action::create([
			'a_action' 		=> 'Please draft reply/memo/letter',
			'a_number'		=> 3,
			'created_at' 	=> '2016-08-24 10:00:00'
		]);

		Action::create([
			'a_action' 		=> 'Please acknowledge receipt',
			'a_number'		=> 4,
			'created_at' 	=> '2016-08-24 10:00:00'
		]);

		Action::create([
			'a_action' 		=> 'Please discuss with me',
			'a_number'		=> 5,
			'created_at' 	=> '2016-08-24 10:00:00'
		]);

		Action::create([
			'a_action' 		=> 'For your information/study/reference',
			'a_number'		=> 12,
			'created_at' 	=> '2016-08-24 10:00:00'
		]);

		Action::create([
			'a_action' 		=> 'For your comments',
			'a_number'		=> 13,
			'created_at' 	=> '2016-08-24 10:00:00'
		]);

		Action::create([
			'a_action' 		=> 'For your initial/signature',
			'a_number'		=> 14,
			'created_at' 	=> '2016-08-24 10:00:00'
		]);

		Action::create([
			'a_action' 		=> 'Please file',
			'a_number'		=> 11,
			'created_at' 	=> '2016-08-24 10:00:00'
		]);

		Action::create([
			'a_action' 		=> 'Please follow up',
			'a_number'		=> 7,
			'created_at' 	=> '2016-08-24 10:00:00'
		]);

		Action::create([
			'a_action' 		=> 'Please act on this',
			'a_number'		=> 8,
			'created_at' 	=> '2016-08-24 10:00:00'
		]);

		Action::create([
			'a_action' 		=> 'Please give me feedback',
			'a_number'		=> 10,
			'created_at' 	=> '2016-08-24 10:00:00'
		]);

		Action::create([
			'a_action' 		=> 'Please calendar',
			'a_number'		=> 6,
			'created_at' 	=> '2016-08-24 10:00:00'
		]);

		Action::create([
			'a_action' 		=> 'Please post',
			'a_number'		=> 9,
			'created_at' 	=> '2016-08-24 10:00:00'
		]);
    }
}
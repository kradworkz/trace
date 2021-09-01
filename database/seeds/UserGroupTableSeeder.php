<?php

use App\Models\UserGroup;
use Illuminate\Database\Seeder;

class UserGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('t_user_groups')->delete();

		UserGroup::create([
			'ug_name' 		=> 'Regional Director',
			'created_at' 	=> '2017-05-03 01:00:18'
		]);

		UserGroup::create([
			'ug_name' 		=> 'Division Head / Unit Chief',
			'created_at' 	=> '2017-05-03 01:00:18'
		]);

		UserGroup::create([
			'ug_name' 		=> 'Document Controller',
			'created_at' 	=> '2017-05-03 01:00:18'
		]);

		UserGroup::create([
			'ug_name' 		=> 'Ordinary User',
			'created_at' 	=> '2017-05-03 01:00:18'
		]);
    }
}

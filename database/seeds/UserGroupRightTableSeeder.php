<?php

use App\Models\UserGroupRight;
use Illuminate\Database\Seeder;

class UserGroupRightTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('t_ug_rights')->delete();

        UserGroupRight::create([
			'ug_id' 		=> 1,
			'ur_id'			=> 1,
			'ugr_view'		=> 1,
			'ugr_add' 		=> 1
			'ugr_edit' 		=> 0,
			'ugr_delete' 	=> 0,
			'created_at' 	=> '2016-08-15 15:56:03'
		]);

    }
}

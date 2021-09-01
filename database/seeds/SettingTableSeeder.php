<?php

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('t_settings')->delete();

        Setting::create([
			's_sysname' 	=> 'Tracking, Retrieval, Archiving of Communications for Efficiency (TRACE)',
			's_abbr'		=> 'TRACE',
			's_pending_days'=> '9',			
			's_background' 	=> 'images/system/background.jpg',
			'created_at' 	=> '2017-05-03 01:00:18'
		]);
    }
}
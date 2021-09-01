<?php

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('t_groups')->delete();

        Group::create([
			'group_name' 		=> 'Office of the Regional Director',
			'created_at' 		=> '2016-08-15 15:56:03'			
		]);

		Group::create([
			'group_name' 		=> 'Office of the Assistant Regional Director for TO',
			'created_at' 		=> '2016-08-15 15:56:03'		
		]);

		Group::create([
			'group_name' 		=> 'Office of the Assistant Regional Director for FAS',
			'created_at' 		=> '2016-08-15 15:56:03'	
		]);

		Group::create([
			'group_name' 		=> 'FAS - Accounting Unit',
			'created_at' 		=> '2016-08-15 15:56:03'
		]);

		Group::create([
			'group_name' 		=> 'FAS - Budget Unit',
			'created_at' 		=> '2016-08-15 15:56:03'
		]);

		Group::create([
			'group_name' 		=> 'FAS - Cashier Unit',
			'created_at' 		=> '2016-08-15 15:56:03'
		]);

		Group::create([
			'group_name' 		=> 'FAS - Property Unit',
			'created_at' 		=> '2016-08-15 15:56:03'
		]);

		Group::create([
			'group_name' 		=> 'FAS - HR Unit',
			'created_at' 		=> '2016-08-15 15:56:03'
		]);

		Group::create([
			'group_name' 		=> 'TO - MIS Unit',
			'created_at' 		=> '2016-08-15 15:56:03'
		]);

		Group::create([
			'group_name' 		=> 'TO - PARCU',
			'created_at' 		=> '2016-08-15 15:56:03'
		]);

		Group::create([
			'group_name' 		=> 'TO - Planning Unit',
			'created_at' 		=> '2016-08-15 15:56:03'
		]);

		Group::create([
			'group_name' 		=> 'TO - Food Safety Unit',
			'created_at' 		=> '2016-08-15 15:56:03'
		]);

		Group::create([
			'group_name' 		=> 'TO - RSTL',
			'created_at' 		=> '2016-08-15 15:56:03'
		]);

		Group::create([
			'group_name' 		=> 'TO - RML',
			'created_at' 		=> '2016-08-15 15:56:03'
		]);

		Group::create([
			'group_name' 		=> 'TO - SETUP',
			'created_at' 		=> '2016-08-15 15:56:03'
		]);

		Group::create([
			'group_name' 		=> 'TO - Scholarship Unit',
			'created_at' 		=> '2016-08-15 15:56:03'
		]);

		Group::create([
			'group_name' 		=> 'Technical Operations',
			'created_at' 		=> '2016-08-15 15:56:03'
		]);

		Group::create([
			'group_name' 		=> 'PSTC - Cavite',
			'created_at' 		=> '2016-08-15 15:56:03'
		]);

		Group::create([
			'group_name' 		=> 'PSTC - Laguna',
			'created_at' 		=> '2016-08-15 15:56:03'
		]);

		Group::create([
			'group_name' 		=> 'PSTC - Batangas',
			'created_at' 		=> '2016-08-15 15:56:03'
		]);

		Group::create([
			'group_name' 		=> 'PSTC - Rizal',
			'created_at' 		=> '2016-08-15 15:56:03'
		]);

		Group::create([
			'group_name' 		=> 'PSTC - Quezon',
			'created_at' 		=> '2016-08-15 15:56:03'
		]);

		Group::create([
			'group_name' 		=> 'Permanent Staff',
			'created_at' 		=> '2017-01-12 09:33:05'
		]);

		Group::create([
			'group_name' 		=> 'Provincial Directors',
			'created_at' 		=> '2017-03-09 15:00:13'
		]);
    }
}

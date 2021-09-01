<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(UserGroupTableSeeder::class);
        $this->call(GroupTableSeeder::class);
        $this->call(UserRightTableSeeder::class);
        //$this->call(UserTableSeeder::class);
        $this->call(SettingTableSeeder::class);
        $this->call(DocumentTypeTableSeeder::class);
        $this->call(ActionTableSeeder::class);

        Model::reguard();
    }
}

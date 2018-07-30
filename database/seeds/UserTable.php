<?php

use Illuminate\Database\Seeder;

class UserTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "DQ",
            'realname' =>"杜琴",
            'email' => "2365160465@qq.com",
            'password' => bcrypt('123456'),
        ]);
    }
}

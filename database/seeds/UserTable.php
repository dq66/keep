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
        $now = date('Y-m-d H:i:s');
        DB::table('users')->insert([
            'name' => "DQ",
            'realname' =>"杜琴",
            'email' => "2365160465@qq.com",
            'password' => bcrypt('123456'),
            'lastlogin_ip'=> null,
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}

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
            'name' => "teeoo",
            'email' => "1982890538@qq.com",
//            'avatar' => "http://q1.qlogo.cn/g?b=qq&nk=1982890538&s=100",
            'password' => bcrypt('123456'),
        ]);
    }
}

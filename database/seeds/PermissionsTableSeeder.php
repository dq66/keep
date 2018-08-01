<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            ['name'=>'Create Ac','guard_name'=>'web'],
            ['name'=>'Edit Ac','guard_name'=>'web'],
            ['name'=>'Delete Ac','guard_name'=>'web'],
            ['name'=>'Create Ty','guard_name'=>'web'],
            ['name'=>'Edit Ty','guard_name'=>'web'],
            ['name'=>'Delete Ty','guard_name'=>'web'],
            ['name'=>'Create Xl','guard_name'=>'web'],
            ['name'=>'Edit Xl','guard_name'=>'web'],
            ['name'=>'Delete Xl','guard_name'=>'web'],
            ['name'=>'Create Cu','guard_name'=>'web'],
            ['name'=>'Edit Cu','guard_name'=>'web'],
            ['name'=>'Delete Cu','guard_name'=>'web'],
            ['name'=>'Create St','guard_name'=>'web'],
            ['name'=>'Edit St','guard_name'=>'web'],
            ['name'=>'Delete St','guard_name'=>'web'],
            ['name'=>'Create Pr','guard_name'=>'web'],
            ['name'=>'Edit Pr','guard_name'=>'web'],
            ['name'=>'Delete Pr','guard_name'=>'web'],
            ['name'=>'Create In','guard_name'=>'web'],
            ['name'=>'Edit In','guard_name'=>'web'],
            ['name'=>'Delete In','guard_name'=>'web'],
            ['name'=>'Create Pa','guard_name'=>'web'],
            ['name'=>'Edit Pa','guard_name'=>'web'],
            ['name'=>'Delete Pa','guard_name'=>'web'],
            ['name'=>'Select Ac','guard_name'=>'web'],
            ['name'=>'Str Ac','guard_name'=>'web'],
            ['name'=>'Sel Tr','guard_name'=>'web'],
            ['name'=>'Create Tr','guard_name'=>'web'],
            ['name'=>'System','guard_name'=>'web'],
            ['name'=>'Administer roles & permissions','guard_name'=>'web'],
        ]);

        DB::table('roles')->insert([
            ['name' => '系统管理','guard_name' => 'web'],
            ['name' => '经理记账','guard_name' => 'web'],
            ['name' => '普通记账','guard_name' => 'web'],
        ]);

        DB::table('role_has_permissions')->insert([
            ['permission_id' => 1,'role_id' => 1],
            ['permission_id' => 2,'role_id' => 1],
            ['permission_id' => 3,'role_id' => 1],
            ['permission_id' => 4,'role_id' => 1],
            ['permission_id' => 5,'role_id' => 1],
            ['permission_id' => 6,'role_id' => 1],
            ['permission_id' => 7,'role_id' => 1],
            ['permission_id' => 8,'role_id' => 1],
            ['permission_id' => 9,'role_id' => 1],
            ['permission_id' => 10,'role_id' => 1],
            ['permission_id' => 11,'role_id' => 1],
            ['permission_id' => 12,'role_id' => 1],
            ['permission_id' => 13,'role_id' => 1],
            ['permission_id' => 14,'role_id' => 1],
            ['permission_id' => 15,'role_id' => 1],
            ['permission_id' => 16,'role_id' => 1],
            ['permission_id' => 17,'role_id' => 1],
            ['permission_id' => 18,'role_id' => 1],
            ['permission_id' => 19,'role_id' => 1],
            ['permission_id' => 20,'role_id' => 1],
            ['permission_id' => 21,'role_id' => 1],
            ['permission_id' => 22,'role_id' => 1],
            ['permission_id' => 23,'role_id' => 1],
            ['permission_id' => 24,'role_id' => 1],
            ['permission_id' => 25,'role_id' => 1],
            ['permission_id' => 26,'role_id' => 1],
            ['permission_id' => 27,'role_id' => 1],
            ['permission_id' => 28,'role_id' => 1],
            ['permission_id' => 29,'role_id' => 1],
            ['permission_id' => 30,'role_id' => 1],
        ]);

        DB::table('model_has_roles')->insert([
            'role_id' => 1,
            'model_type' => 'App\User',
            'model_id' => 1
        ]);
    }
}

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

        //系统用户
        for ($i = 1; $i <= 30; $i++) {
            DB::table('role_has_permissions')->insert([
                'permission_id' => $i,
                'role_id' => 1
            ]);
        }
        //经理记账
        for($i = 1; $i <= 30; $i++){
            if($i != 29){
                DB::table('role_has_permissions')->insert([
                    'permission_id' => $i,
                    'role_id' => 2
                ]);
            }
        }
        //普通记账
        for($i = 1; $i < 24; $i++){
            if($i != 3 && $i != 6 && $i != 9 && $i != 12 && $i != 15 && $i != 18 && $i != 21){
                DB::table('role_has_permissions')->insert([
                    'permission_id' => $i,
                    'role_id' => 3
                ]);
            }
        }

        DB::table('model_has_roles')->insert([
            'role_id' => 1,
            'model_type' => 'App\User',
            'model_id' => 1
        ]);
    }
}

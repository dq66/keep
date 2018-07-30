<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// 引入 laravel-permission 模型
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct() {
        $this->middleware(['isAdmin']); // isAdmin 中间件让具备指定权限的用户才能访问该资源

    }

    public function index() {

        return view('roles.index');
    }
    public function sel(){
        $roles = Role::all();// 获取所有角色
        //dump($roles);
        $data = array();
        foreach ($roles as $role){
            $role->perm = $role->permissions()->pluck('name');
            $data[]= $role;
        }
        $count = Role::count();
        //dump($data);
        return response()->json(['data'=>$data,'total'=>$count]);
    }

    public function create(Request $request) {
        //验证 name 和 permissions 字段
        $this->validate($request, [
                'name'=>'required|unique:roles|max:10',
                'permissions' =>'required',
            ]
        );

        $name = $request['name'];
        $role = new Role();
        $role->name = $name;

        $permissions = $request['permissions'];

        $role->save();
        // 遍历选择的权限
        foreach ($permissions as $permission) {
            $p = Permission::where('id', '=', $permission)->firstOrFail();
            // 获取新创建的角色并分配权限
            $role = Role::where('name', '=', $name)->first();
            $role->givePermissionTo($p);
        }
        return Prompt($permissions,'添加数据','Admin/Role');
    }

    //获取所有权限
    public function per(){
        $permissions = Permission::all();// 获取所有权限

        return response()->json(['data'=>$permissions]);
    }

    public function edit(Request $request) {
        $p_all = Permission::all();//获取所有权限
        if($request->isMethod('post')){
            $role = Role::findOrFail($request->input('id')); // 通过给定id获取角色
            // 验证 name 和 permission 字段
            $this->validate($request, [
                'name'=>'required|max:10|unique:roles,name,'.$request->input('id'),
                'permissions' =>'required',
            ]);

            $input = $request->except(['permissions']);
            $permissions = $request['permissions'];
            $role->fill($input)->save();

            foreach ($p_all as $p) {
                $role->revokePermissionTo($p); // 移除与角色关联的所有权限
            }

            foreach ($permissions as $permission) {
                $p = Permission::where('id', '=', $permission)->firstOrFail(); //从数据库中获取相应权限
                $role->givePermissionTo($p);  // 分配权限到角色
            }
            return Prompt($role,'编辑数据','Admin/Role');
        }else{
            $role = Role::find($request->id);
            $role['pe'] = $role->permissions()->pluck('id');

            return response()->json(['data'=>$role,'perm'=>$p_all]);
        }
    }

    public function del($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        if($role){
            return response()->json(['success'=>true,'msg'=>'删除成功']);
        }else{
            return response()->json(['success'=>false,'msg'=>'删除失败！']);
        }
    }
    //批量删除
    public function delall(Request $request){
        $role = \DB::delete('delete from roles where id in ('.$request->get('ids').')');
        if($role){
            return response()->json(['success'=>true,'msg'=>'批量删除成功']);
        }else{
            return response()->json(['success'=>false,'msg'=>'批量删除失败！']);
        }
    }
}

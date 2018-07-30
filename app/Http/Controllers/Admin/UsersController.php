<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
// 引入 laravel-permission 模型
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function __construct() {
        $this->middleware(['isAdmin']); // isAdmin 中间件让具备指定权限的用户才能访问该资源
    }

    public function index(){
        return view('users.index');
    }
    public function sel(Request $request){
        $search = $request->input('search');
        if(isset($search)){//判断是否搜索查询
            $users = User::where('name','like','%'.$search.'%')->orwhere('email','like','%'.$search.'%')->get();
            $count = User::where('name','like','%'.$search.'%')->orwhere('email','like','%'.$search.'%')->count();
        }else{
            $users = User::all();
            $count= User::count();
        }

        $data = array();
        foreach ($users as $use){
            $use->role = $use->roles()->pluck('name');
            $data[]= $use;
        }
        return response()->json(['data'=>$data,'total'=>$count]);
    }

    public function create(Request $request){
        //dump($request->all());

        $user = User::create($request->only('name','realname','email','password'));//只获取指定字段

        $roles = $request['roles']; // 获取输入的角色字段
        // 检查是否某个角色被选中
        if (isset($roles)) {
            foreach ($roles as $role) {
                $role_r = Role::where('id', '=', $role)->firstOrFail();
                $user->assignRole($role_r); //Assigning role to user
            }
        }

        return Prompt($user,'添加用户','Admin/Users');
    }
    // 获取所有角色
    public function rol(){
        $roles = Role::get();
        return response()->json(['data'=>$roles]);
    }

    public function edit(Request $request){
        if($request->isMethod('post')){
            $user = User::findOrFail($request->id); // 通过id获取给定角色

            $input = $request->only(['name','realname', 'email', 'password']); // 获取 name, email 和 password 字段
            $roles = $request['roles']; // 获取所有角色
            $user->fill($input)->save();

            if (isset($roles)) {
                $user->roles()->sync($roles);  // 如果有角色选中与用户关联则更新用户角色
            } else {
                $user->roles()->detach(); // 如果没有选择任何与用户关联的角色则将之前关联角色解除
            }
            return Prompt($user,'编辑用户','Admin/Users');
        }else{
            $user = User::find($request->id); // 通过给定id获取用户
            $roles = Role::get(); // 获取所有角色
            $user['rol'] = $user->roles()->pluck('id');
            return response()->json(['data'=>$user,'roles'=>$roles]);
        }
    }

    public function del($id){
        // 通过给定id获取并删除用户
        $user = User::where('id','=',$id)->delete();
        if($user){
            return response()->json(['success'=>true,'msg'=>'删除成功']);
        }else{
            return response()->json(['success'=>false,'msg'=>'删除失败！']);
        }

    }

}

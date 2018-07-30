<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// 引入 laravel-permission 模型
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct() {
        $this->middleware(['isAdmin']); // isAdmin 中间件让具备指定权限的用户才能访问该资源
    }
    /**
     * 显示权限列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('permissions.index');
    }

    public function sel(){
        $permissions = Permission::all(); // 获取所有权限
        $count = Permission::count();
        return response()->json(['data'=>$permissions,'total'=>$count]);
    }


    public function create(Request $request) {
        $this->validate($request, [
            'name'=>'required|max:40',
        ]);

        $name = $request['name'];
        $permission = new Permission();
        $permission->name = $name;

        $roles = $request['roles'];

        $permission->save();

        if (!empty($request['roles'])) { // 如果选择了角色
            foreach ($roles as $role) {
                $r = Role::where('id', '=', $role)->firstOrFail(); // 将输入角色和数据库记录进行匹配

                $permission = Permission::where('name', '=', $name)->first(); // 将输入权限与数据库记录进行匹配
                $r->givePermissionTo($permission);
            }
        }

        return Prompt($permission,'添加角色','Admin/Permissions');

    }

    public function edit(Request $request) {

        if($request->isMethod('post')){
            $this->validate($request, [
                'name'=>'required|max:40',
            ]);

            $permission =Permission::where('id','=',$request->post('id'))
                ->update(['name'=>$request->post('name')]);

            return Prompt($permission,'编辑角色','Admin/Permissions');

        }else{
            $permission = Permission::find($request->id);
            return response()->json(['data'=>$permission]);
        }
    }



    public function del($id) {
        $permission = Permission::findOrFail($id);

        // 让特定权限无法删除
        if ($permission->name == "Administer roles & permissions") {
            return response()->json(['success'=>false,'msg'=>'对不起！你没有权限']);
        }

        if($permission->delete()){
            return response()->json(['success'=>true,'msg'=>'删除成功']);
        }else{
            return response()->json(['success'=>false,'msg'=>'删除失败！']);
        }
    }
    public function delall(Request $request){
        $per = \DB::delete('delete from permissions where id in ('.$request->get('ids').')');
        if($per){
            return response()->json(['success'=>true,'msg'=>'批量删除成功']);
        }else{
            return response()->json(['success'=>false,'msg'=>'批量删除失败！']);
        }
    }
}

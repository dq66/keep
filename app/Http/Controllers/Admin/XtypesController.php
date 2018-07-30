<?php

namespace App\Http\Controllers\Admin;

use App\Model\Types;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class XtypesController extends Controller
{
    public function index(){
        return view('xtypes.index');
    }
    public function sel(Request $request){
        $rows= $request->get('limit');
        $page = $request->get('pageNumber');
        $px = $request->get('order');
        $name = $request->get('sort');
        $search = $request->get('search');

        $count= Types::where('parent','!=',0)->count();

        if(!isset($rows) and !isset($page)){
            $data = Types::where("parent","=",0)->get();
        }else if(isset($search)){//判断是否是搜索查询
            $data = Types::where('parent','!=',0)->where('name','like','%'.$search.'%')->paginate($rows,['*'],1,$page);
            $count= Types::where('parent','!=',0)->where('name','like','%'.$search.'%')->count();
        }else if(isset($name)){//判断是否要排序
            $data = Types::where('parent','!=',0)->orderBy($name,$px)->paginate($rows,['*'],1,$page);
        }else{
            $data = Types::where('parent','!=',0)->paginate($rows,['*'],1,$page);
        }
        $all = array();
        $types = Types::all();
        foreach ($data as $da){
            foreach ($types as $ty){
                if($da->parent == $ty->id){
                    $da->parent = $ty->name;
                    $all[]=$da;
                }
            }
        }
        //dump($all);
        return response()->json(['data'=>$all,'total'=>$count]);
    }

    //添加小类
    public function create(Request $request){

        $types = Types::where('id','=',$request->post('parent'))->first();
        $input = $request->except(['_token']);

        $xtypes = $types->createChild($input);

        return Prompt($xtypes,'添加数据','Admin/Xtypes');

    }

    //修改小类
    public function edit(Request $request){
        if($request->isMethod('post')){
            $input = $request->except(['_token']);
            $xtype = Types::where('id','=',$request->id)->update($input);
            //修复树关联
            Types::find($request->id)->perfectTree();
            //清理冗余的关联信息
            Types::deleteRedundancies();

            return Prompt($xtype,'编辑数据','Admin/Xtypes');

        }else{
            $xtype = Types::find($request->id);
            return response()->json(['success'=>true,'data'=>$xtype]);
        }
    }

    //删除小类
    public function del($id){
        $xtype = Types::where('id','=',$id)->delete();
        // 清理冗余的关联信息
        Types::deleteRedundancies();
        if($xtype){
            return response()->json(['success' => true,'msg'=>'删除成功']);
        }else{
            return response()->json(['success' => false,'msg'=>'删除失败！']);
        }
    }
    //批量删除
    public function delall(Request $request){
        $xtype = \DB::delete('delete from types where id in ('.$request->get('ids').')');
        if($xtype){
            return response()->json(['success'=>true,'msg'=>'批量删除成功']);
        }else{
            return response()->json(['success'=>false,'msg'=>'批量删除失败！']);
        }
    }
}

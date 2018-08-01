<?php

namespace App\Http\Controllers\Admin;

use App\Model\Projects;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectsController extends Controller
{
    public function index(){
        return view('projects.index');
    }
    public function sel(Request $request){
        $rows= $request->get('limit');
        $page = $request->get('offset');
        $px = $request->get('order');
        $name = $request->get('sort');
        $search = $request->get('search');

        $count= Projects::count();

        if(!isset($rows) and !isset($page)){
            $data = Projects::all();
        }else if(isset($search)){//判断是否是搜索查询
            $a = Projects::where('name','like','%'.$search.'%')->paginate($rows,['*'],1,$page)->toArray();
            $data = $a['data'];
            $count= Projects::where('name','like','%'.$search.'%')->count();
        }else if(isset($name)){//判断是否要排序
            $b = Projects::orderBy($name,$px)->paginate($rows,['*'],1,$page)->toArray();
            $data = $b['data'];
        }else{
            $c = Projects::paginate($rows,['*'],1,$page)->toArray();
            $data = $c['data'];
        }
        return response()->json(['data'=>$data,'total'=>$count]);
    }

    //添加项目
    public function create(Request $request){
        $pr = Projects::create($request->post());

        return Prompt($pr,'添加数据','Admin/Projects');
    }
    //修改项目
    public function edit(Request $request){
        if ($request->isMethod('post')){

            $input = $request->except(['_token']);
            $pr = Projects::where('id','=',$request->id)->update($input);

            return Prompt($pr,'编辑数据','Admin/Projects');

        }else{
            $pr = Projects::find($request->id);
            return response()->json(['success' => true, 'data'=>$pr]);
        }
    }

    //导入
    public function import(Request $request){
        dump($request->all());
        //文件名称
        $file = $request->file('file');



    }

    //删除项目
    public function del($id){
        $pr = Projects::where('id','=',$id)->delete();
        if($pr){
            return response()->json(['success' => true,'msg'=>'删除成功']);
        }else{
            return response()->json(['success' => false,'msg'=>'删除失败！']);
        }
    }
    //批量删除
    public function delall(Request $request){
        $pr = \DB::delete('delete from projects where id in ('.$request->get('ids').')');
        if($pr){
            return response()->json(['success'=>true,'msg'=>'批量删除成功']);
        }else{
            return response()->json(['success'=>false,'msg'=>'批量删除失败！']);
        }
    }
}

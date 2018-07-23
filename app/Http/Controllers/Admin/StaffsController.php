<?php

namespace App\Http\Controllers\Admin;

use App\Model\Staffs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StaffsController extends Controller
{
    public function index()
    {
        return view('staffs.index');
    }
    public function sel(Request $request){
        $rows= $request->get('limit');
        $page = $request->get('pageNumber');
        $px = $request->get('order');
        $name = $request->get('sort');
        $search = $request->get('search');

        $count= Staffs::count();

        if(!isset($rows) and !isset($page)){
            $data = Staffs::all();
        }else if(isset($search)){//判断是否是搜索查询
            $a = Staffs::where('name','like','%'.$search.'%')->orwhere('department','like','%'.$search.'%')
                ->paginate($rows,['*'],1,$page)->toArray();
            $data = $a['data'];
            $count= Staffs::where('name','like','%'.$search.'%')->count();
        }else if(isset($name)){//判断是否要排序
            $b = Staffs::orderBy($name,$px)->paginate($rows,['*'],1,$page)->toArray();
            $data = $b['data'];
        }else{
            $c = Staffs::paginate($rows,['*'],1,$page)->toArray();
            $data = $c['data'];
        }

        return response()->json(['data'=>$data,'total'=>$count]);
    }

    //添加员工
    public function create(Request $request){

        $st = Staffs::create($request->post());

        return Prompt($st,'添加数据','Admin/Staffs');
    }
    //编辑员工
    public function edit(Request $request){
        if($request->isMethod('post')){

            $input = $request->except(['_token']);
            $st = Staffs::where('id','=',$request->id)->update($input);

            return Prompt($st,'编辑数据','Admin/Staffs');

        }else{
            $st = Staffs::find($request->id);
            return response()->json(['success'=>true,'data'=>$st]);
        }
    }
    //删除员工
    public function del($id){

        $st = Staffs::where('id','=',$id)->delete();
        if($st){
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['success'=>false]);
        }
    }}

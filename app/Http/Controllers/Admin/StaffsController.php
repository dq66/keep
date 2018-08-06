<?php

namespace App\Http\Controllers\Admin;

use App\Model\Staffs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;

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
            return response()->json(['success'=>true,'msg'=>'删除成功']);
        }else{
            return response()->json(['success'=>false,'msg'=>'删除失败！']);
        }
    }
    //批量删除
    public function delall(Request $request){
        $st = \DB::delete('delete from staffs where id in ('.$request->get('ids').')');
        if($st){
            return response()->json(['success'=>true,'msg'=>'批量删除成功']);
        }else{
            return response()->json(['success'=>false,'msg'=>'批量删除失败！']);
        }
    }
    //导入
    public function import(Request $request){
        //dump($request->all());
        if($request->file('file')){

            $file = $_FILES;
            $excel_file_path = $file['file']['tmp_name'];
            $st = '';

            Excel::load($excel_file_path, function($reader) use( &$st ){
                $reader = $reader->getSheet(0);
                $res = $reader->toArray();
                //dump($res);
                foreach ($res as $key => $value){
                    $data = array(
                        'name' => $value[0],
                        'telephone' => $value[1],
                        'birth' => $value[2],
                        'department' => $value[3],
                        'desc' => $value[4]
                    );
                    if($key > 0){
                        $st = Staffs::create($data);
                    }
                }
            });
            return Prompt($st,'导入数据','Admin/Customer');
        }
    }
}

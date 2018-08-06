<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CustomerRequest;
use App\Model\Customers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Excel;

class CustomerController extends Controller
{
    public function index()
    {
        $customer=Customers::paginate(5);
        return view("customer.index",compact('customer'));
    }
    public function sel(Request $request){
        $rows= $request->get('limit');
        $page = $request->get('pageNumber');
        $px = $request->get('order');
        $name = $request->get('sort');
        $search = $request->get('search');

        $count= Customers::count();

        if(!isset($rows) and !isset($page)){
            $data = Customers::where('parent','=',0)->get();
        }else if(isset($search)){//判断是否是搜索查询
            $a = Customers::where('name','like','%'.$search.'%')->paginate($rows,['*'],1,$page)->toArray();
            $data = $a['data'];
            $count= Customers::where('name','like','%'.$search.'%')->count();
        }else if(isset($name)){//判断是否要排序
            $b = Customers::orderBy($name,$px)->paginate($rows,['*'],1,$page)->toArray();
            $data = $b['data'];
        }else{
            $c = Customers::paginate($rows,['*'],1,$page)->toArray();
            $data = $c['data'];
        }
        //dump($data);
        return response()->json(['data'=>$data,'total'=>$count]);
    }

    //添加客户
    public function create(CustomerRequest $request)
    {
        $cu=Customers::create($request->post());

        return Prompt($cu,'添加数据','Admin/Customer');
    }
    //编辑客户
    public function edit(Request $request){

        if($request->isMethod('post')){
            //dd($request->all());
            $input = $request->except(['_token']);
            $cu = Customers::where('id','=',$request->id)->update($input);

            return Prompt($cu,'编辑数据','Admin/Customer');

        }else{
            $cu = Customers::find($request->id);
            return response()->json(['success'=>true,'data'=>$cu]);
        }
    }
    //删除客户
    public function del($id){
        $cu = Customers::where('id','=',$id)->delete();
        if($cu){
            return response()->json(['success'=>true,'msg'=>'删除成功']);
        }else{
            return response()->json(['success'=>false,'msg'=>'删除失败！']);
        }
    }
    //批量删除
    public function delall(Request $request){
        $cu = \DB::delete('delete from customers where id in ('.$request->get('ids').')');
        if($cu){
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
            $cu = '';

            Excel::load($excel_file_path, function($reader) use( &$cu ){
                $reader = $reader->getSheet(0);
                $res = $reader->toArray();
                //dump($res);
                foreach ($res as $key => $value){
                    $data = array(
                        'name' => $value[0],
                        'telephone' => $value[1],
                        'fax' => $value[2],
                        'email' => $value[3],
                        'address' => $value[4],
                        'desc' => $value[5]
                    );
                    if($key > 0){
                        $cu = Customers::create($data);
                    }
                }
            });
            return Prompt($cu,'导入数据','Admin/Customer');
        }
    }
}

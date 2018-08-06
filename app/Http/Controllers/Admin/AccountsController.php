<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AccountsRequest;
use App\Model\Accounts;
use App\Model\Incomes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;

class AccountsController extends Controller
{
    public function index(){
        return view('accounts.index');
    }
    public function sel(Request $request){
        $rows= $request->get('limit');
        $page = $request->get('pageNumber');
        $px = $request->get('order');
        $name = $request->get('sort');
        $search = $request->get('search');

        $count= Accounts::count();
        if(!isset($rows) and !isset($page)){
            $data = Accounts::all();
        }else if(isset($search)){//判断是否是搜索查询
            $a = Accounts::where('name','like','%'.$search.'%')->paginate($rows,['*'],1,$page)->toArray();
            $data = $a['data'];
            $count= Accounts::where('name','like','%'.$search.'%')->count();
        }else if(isset($name)){//判断是否要排序
            $b = Accounts::orderBy($name,$px)->paginate($rows,['*'],1,$page)->toArray();
            $data = $b['data'];
        }else{
            $c = Accounts::paginate($rows,['*'],1,$page)->toArray();
            $data = $c['data'];
        }

        return response()->json(['data'=>$data,'total'=>$count]);
    }

    //添加账户
    public function create(AccountsRequest $request){
        //dd($request->all());
        $accounts = Accounts::create(array_merge($request->post(),['balance' => $request->input('money')]));

        return Prompt($accounts,'添加数据','Admin/Accounts');

    }
    //修改账户
    public function edit(Request $request){

        if($request->isMethod('post')){
            $input = $request->except(['_token']);
            $accounts = Accounts::where('id','=',$request->id)->update($input);

            return Prompt($accounts,'编辑数据','Admin/Accounts');

        }else{
            $accounts = Accounts::find($request->id);
            return response()->json(['success'=>true,'data'=>$accounts]);
        }
    }
    //删除账户
    public function del($id){
        $accounts = Accounts::where('id','=',$id)->delete();
        if($accounts){
            return response()->json(['success'=>true,'msg'=>'删除成功']);
        }else{
            return response()->json(['success'=>false,'msg'=>'删除失败！']);
        }

    }
    //批量删除
    public function delall(Request $request){
        $acc = \DB::delete('delete from accounts where id in ('.$request->get('ids').')');
        if($acc){
            return response()->json(['success'=>true,'msg'=>'批量删除成功']);
        }else{
            return response()->json(['success'=>false,'msg'=>'批量删除失败！']);
        }
    }

    //资金管理
    public function capital(){
        return view('accounts.capital');
    }

    //收支流水详情
    public function stream(Request $request){
//        dump($request->all());
        $type = $request->get('type');
        $inc = Incomes::with('users')->with('accounts')->where('accounts_id','=',$type)->get();

        $count = Incomes::where('accounts_id','=',$type)->count();
        $data = array();
        foreach ($inc as $da){
            $da->users_id = $da->users->name;
            $da->accounts_id = $da->accounts->name;
            $data[]=$da;
        }
        return response()->json(['data'=>$data,'total'=>$count]);
    }
    //转账（转入账户）
    public function stans($id){
        $inc = Accounts::where('id','!=',$id)->get();
        return response()->json(['data' => $inc]);
    }
    //导入
    public function import(Request $request){
        //dump($request->all());
        if($request->file('file')){

            $file = $_FILES;
            $excel_file_path = $file['file']['tmp_name'];
            $inc = '';

            Excel::load($excel_file_path, function($reader) use( &$inc ){
                $reader = $reader->getSheet(0);
                $res = $reader->toArray();
                foreach ($res as $key => $value){
                    $type = $value[0] == "现金" ? 1:2;
                    //dump($type);
                    $data = array(
                        'type' => $type,
                        'name' => $value[1],
                        'money' => $value[3],
                        'balance' => $value[4],
                        'desc' => $value[5]
                    );
                    if($key > 0){
                        $inc = Accounts::create($data);
                    }
                }
            });
            return Prompt($inc,'导入数据','Admin/Accounts');
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AccountsRequest;
use App\Model\Accounts;
use App\Model\Incomes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $accounts = Accounts::create($request->post());

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
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['success'=>false]);
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
}

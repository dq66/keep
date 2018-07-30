<?php

namespace App\Http\Controllers\Admin;

use App\Model\Accounts;
use App\Model\Customers;
use App\Model\Payments;
use App\Model\Projects;
use App\Model\Staffs;
use App\Model\Types;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentsController extends Controller
{
    public function index(){
        //总收入跟总支出
        $enter = \DB::select("SELECT SUM(money) ent FROM payments WHERE is_types=1");
        $come = \DB::select("SELECT SUM(money) com  FROM payments WHERE is_types=2");

        //判断年收入跟年支出是否为空
        if(empty($enter) and empty($come)){
            $profit = array(['msg' => '盈利\亏盈', 'data' => 0]);
        }else{
            if (!isset($enter[0]->ent)){
                $data = $come[0]->com;
                $profit = array(['msg' => '亏盈', 'data' => $data]);
            }else{
                if($enter[0]->ent > $come[0]->com){
                    $data = $enter[0]->ent - $come[0]->com;
                    $profit = array(['msg' => '盈利', 'data' => $data]);
                }else{
                    $data = $come[0]->com - $enter[0]->ent;
                    $profit = array(['msg' => '亏盈', 'data' => $data]);
                }
            }
        }
        //dump($enter[0]->ent,$come);
        return view('payments.index',compact('enter','come','profit'));
    }
    public function sel(Request $request){
        $rows= $request->get('limit');
        $page = $request->get('offset');
        $px = $request->get('order');
        $name = $request->get('sort');
        $search = $request->get('search');

        $count= Payments::count();

        if(!isset($rows) and !isset($page)){
            $data  = Payments::with('types')->with('customers')->with('projects')->with('staffs')->with('users')->get();
        }else if(isset($search)){//判断是否是搜索查询
            $data  = Payments::with('types')->with('customers')->with('projects')->with('staffs')->with('users')
                ->where('money','like','%'.$search.'%')->paginate($rows,['*'],1,$page);
            $count= Payments::where('money','like','%'.$search.'%')->count();
        }else if(isset($name)){//判断是否要排序
           $data  = Payments::with('types')->with('customers')->with('projects')
               ->with('staffs')->with('users')->orderBy($name,$px)->paginate($rows,['*'],1,$page);
        }else{
            $data  = Payments::with('types')->with('customers')->with('projects')->with('staffs')
                ->with('users')->orderBy('created_at',$px)->paginate($rows,['*'],1,$page);
        }
        $xtype = Types::where('parent','>',0)->get();
        $all=array();
        foreach ($data as $da){
            $da->types_id = $da->types->name;
            $da->customers_id = $da->customers->name;
            $da->projects_id = $da->projects->name;
            $da->staffs_id  = $da->staffs->name;
            $da->users_id = $da->users->name;
            foreach ($xtype as $xl){
                if($da->parent_id == $xl->id){
                    $da->parent_id=$xl->name;
                    $all[]=$da;
                }
            }
        }
        //dump($data);
        return response()->json(['data'=>$all,'total'=>$count]);
    }

    public function add(){
        //生意伙伴
        $cust = Customers::all();
        //业务项目projects_id
        $pro = Projects::all();
        //经办人staffs_id
        $sta = Staffs::all();
        return view('payments.add',compact('cust','pro','sta'));
    }
    public function create(Request $request){
        //dd($request->post());
        $pay = Payments::create(array_merge($request->post(),['users_id' => \Auth::user()->id]));

        return Prompt($pay,'添加数据','Admin/Payments/add');
    }

    public function edit(Request $request){
        if($request->isMethod('post')){
            //dd($request->post());
            $input = $request->except(['_token']);
            $pay = Payments::where('id','=',$request->id)
                ->update(array_merge($input,['users_id' => \Auth::user()->id]));

            return Prompt($pay,'编辑数据','Admin/Payments');

        }else{
            //生意伙伴
            $cust = Customers::all();
            //业务项目projects_id
            $pro = Projects::all();
            //经办人staffs_id
            $sta = Staffs::all();
            $pay = Payments::find($request->id);
            return view('payments.edit',compact('cust','pro','sta','pay'));
        }
    }

    public function del($id){

        if(Payments::where('id','=',$id)->delete()){
            return response()->json(['success' => true,'msg'=>'删除成功']);
        }else{
            return response()->json(['success' => false,'msg'=>'删除失败！']);
        }
    }
}

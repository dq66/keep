<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Model\Accounts;
use App\Model\Transfers;
use App\User;
use App\Http\Controllers\Controller;

class TransfersController extends Controller
{
    public function index(Request $request){
        $trans = Transfers::with('accounts')->with('users')
            ->where('turn_out','=',$request->get('id'))
            ->orWhere('accounts_id','=',$request->get('id'))
            ->get();
        $data = array();
        $accounts = Accounts::all();
        foreach ($trans as $tr){
            $tr->accounts_id = $tr->accounts->name;
            $tr->users_id = $tr->users->name;
            foreach ($accounts as $ac){
                if($tr->turn_out == $ac->id){
                    $tr->turn_out = $ac->name;
                }
            }
            $data[]=$tr;
        }
        return response()->json(['data'=>$data]);
    }

    public function create(Request $request){
        //dump($request->all());
        //首先根据转出的账户查出之前的余额 并扣除转出的金额
        $acc_zc = Accounts::find($request->input('turn_out'));
        $money = $acc_zc->balance - $request->input('money');
        Accounts::where('id','=',$request->input('turn_out'))->update(['balance'=>$money]);
        //再根据转入账户查出之前的余额 并加上现在转入金额
        $acc_zr = Accounts::find($request->input('accounts_id'));
        $zr_money = $acc_zr->balance + $request->input('money');
        Accounts::where('id','=',$request->input('accounts_id'))->update(['balance'=>$zr_money]);
        //最后再把这条记录添加到数据库里
        //转出
        $trans = Transfers::create(array_merge($request->post(),['users_id' => \Auth::user()->id,'is_types'=>2]));
        //转入
        $data = [
            'is_types'=>1,
            'money'=>$request->input('money'),
            'turn_out'=>$request->input('accounts_id'),
            'accounts_id'=>$request->input('turn_out'),
            'users_id'=>\Auth::user()->id,
            'desc'=>$request->input('desc')
        ];
        Transfers::create($data);
        return Prompt($trans,'转账','Admin/Accounts/capital');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Model\Accounts;
use App\Model\Customers;
use App\Model\Incomes;
use App\Model\Projects;
use App\Model\Staffs;
use App\Model\Types;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IncomesController extends Controller
{
    public function index()
    {
        //总收入跟总支出
        $enter = \DB::select("SELECT SUM(money) ent FROM incomes WHERE is_types=1");
        $come = \DB::select("SELECT SUM(money) com  FROM incomes WHERE is_types=2");

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
        //查询所有大类
        $types = Types::where('parent','=',0)->get();
        //查询所有小类
        $xtype = Types::where('parent','>', 0)->get();
        //所有账户
        $accounts = Accounts::all();
        //所有生意伙伴
        $customer = Customers::all();
        //所有项目
        $projects = Projects::all();
        //所有管理员
        $users = User::all();

        return view('incomes.index', compact( 'enter','come','profit','types','xtype','accounts','customer','projects','users'));
    }
    public function sel(Request $request){
        $rows= $request->get('limit');
        $page = $request->get('pageNumber');
        $px = $request->get('order');
        $name = $request->get('sort');
        $search = $request->get('search');

        $is_types = $request->get('is_types');
        $types_id = $request->get('types_id');
        $xtype_id = $request->get('parent_id');
        $accounts_id = $request->get('accounts_id');
        $customer_id = $request->get('customer_id');
        $projects_id = $request->get('projects_id');
        $users_id =$request->get('users_id');
        $starttime = $request->get('starttime');
        $endtime = $request->get('endtime');

        if(!isset($rows) and !isset($page)){
            $inc = Incomes::with('types')->with('accounts')->with('customers')
                ->with('projects')->with('staffs')->with('users')->get();
            $count= Incomes::count();
        }else if(isset($search)){//判断是否是搜索查询
            $inc = Incomes::with('types')->with('accounts')->with('customers')
                ->with('projects')->with('staffs')->with('users')
                ->where('money','like','%'.$search.'%')->paginate($rows,['*'],1,$page);
            $count= Incomes::where('money','like','%'.$search.'%')->count();
        }else if(isset($name)){//判断是否要排序
            $inc = Incomes::with('types')->with('accounts')->with('customers')
                ->with('projects')->with('staffs')->with('users')
                ->orderBy($name,$px)->paginate($rows,['*'],1,$page);
            $count= Incomes::count();

        }else if(isset($is_types) && $is_types!='all' || isset($types_id) && $types_id!='all' || isset($xtype_id)&& $xtype_id!='all' || isset($accounts_id)&& $accounts_id!='all' || isset($customer_id)&& $customer_id!='all' || isset($projects_id)&& $projects_id!='all' || isset($users_id)&& $users_id!='all' || isset($starttime) && isset($endtime)){
            //高级查询
            $input = $request->except(['order','pageNumber','offset','limit','starttime','endtime']);

            $tj =array();
            foreach ($input as $j=>$v){
                if($v != 'all'){
                    $tj[$j]=$v;
                }
            }
            if(!empty($starttime) && !empty($endtime)) {
                if($tj) {
                    $inc = Incomes::with('types')->with('accounts')->with('customers')->with('projects')
                        ->with('staffs')->with('users')->where($tj)->where('created_at', '>', $starttime)
                        ->where('created_at', '<', $endtime)
                        ->orderBy('created_at', $px)->paginate($rows, ['*'], 1, $page);
                    $count= Incomes::where($tj)->where('created_at', '>', $starttime)
                        ->where('created_at', '<', $endtime)->count();
                }else{
                    $inc = Incomes::with('types')->with('accounts')->with('customers')->with('projects')
                        ->with('staffs')->with('users')->where('created_at', '>', $starttime)
                        ->where('created_at', '<', $endtime)
                        ->orderBy('created_at', $px)->paginate($rows, ['*'], 1, $page);
                    $count= Incomes::where('created_at', '>', $starttime)->where('created_at', '<', $endtime)->count();
                }

            }else{
                if($tj){
                    $inc = Incomes::with('types')->with('accounts')->with('customers')->with('projects')
                        ->with('staffs')->with('users')->where($tj)
                        ->orderBy('created_at', $px)->paginate($rows, ['*'], 1, $page);
                    $count= Incomes::where($tj)->count();
                }
            }


        }else{
            $inc = Incomes::with('types')->with('accounts')->with('customers')->with('projects')
                ->with('staffs')->with('users')->orderBy('created_at',$px)->paginate($rows,['*'],1,$page);
            $count= Incomes::count();
        }
        $xtype = Types::where('parent', '>', 0)->get();
        $all = array();
        //dump($inc);
        foreach ($inc as $in){
            $in->types_id = $in->types->name;
            $in->accounts_id = $in->accounts->name;
            $in->customers_id = $in->customers->name;
            $in->projects_id = $in->projects->name;
            $in->staffs_id  = $in->staffs->name;
            $in->users_id = $in->users->name;
            foreach ($xtype as $xt){
                if($in->parent_id == $xt->id){
                    $in->parent_id = $xt->name;
                    $all[]=$in;
                }
            }
        }

        return response()->json(['data'=>$all,'total'=>$count]);

    }

    public function add()
    {
        //账户
        $acc = Accounts::all();
        //生意伙伴
        $cust = Customers::all();
        //业务项目projects_id
        $pro = Projects::all();
        //经办人staffs_id
        $sta = Staffs::all();

        return view('incomes.add', compact('acc', 'cust', 'pro', 'sta'));
    }

    public function create(Request $request)
    {

        //dd($request->post());
        $inc = Incomes::create(array_merge($request->post(), ['users_id' => \Auth::user()->id]));

        //首先判断是收入还是支出 如果是收入就在原来的账户里加上现在的 反之就是拿原来的减去现在的
        $acc = Accounts::find($request->accounts_id);
        //dump($acc->balance);
        $bal = $request->is_types == 1 ? $acc->balance + $request->money : $acc->balance - $request->money;

        Accounts::where('id', '=', $request->accounts_id)->update(['balance' => $bal]);

        return Prompt($inc, '添加数据', 'Admin/Incomes/add');

    }

    public function edit(Request $request)
    {
        $inc = Incomes::find($request->id);

        if ($request->isMethod('post')) {
            $input = $request->except(['_token']);
            $incs = Incomes::where('id', '=', $request->id)->update(array_merge($input, ['users_id' => \Auth::user()->id]));

            //计算金额
            $this->Calculation($request->money, $inc['money'], $inc['is_types'], $request->is_types, $request->accounts_id);

            return Prompt($incs, '编辑数据', 'Admin/Incomes');

        } else {
            //账户
            $acc = Accounts::all();
            //生意伙伴
            $cust = Customers::all();
            //业务项目projects_id
            $pro = Projects::all();
            //经办人staffs_id
            $sta = Staffs::all();

            //dump($inc);
            return view('incomes.edit', compact('inc', 'acc', 'cust', 'pro', 'sta'));
        }
    }

    /**
     * Notes:计算金额
     * User:Administrator
     * Date:2018/5/30
     * Time:16:52
     * @param $xzbal 现在的金额
     * @param $zqbal 之前的金额
     * @param $ytype 原来的类型（收入、支出）
     * @param $type  现在的类型
     * @param $acc_id 现在的账户id
     */
    public function Calculation($xzbal, $zqbal, $ytype, $type, $acc_id)
    {

        $acc = Accounts::find($acc_id);
        //首先判断之前的类型跟现在的类型（收入\支出）是否相同
        if ($ytype == $type) {
            //首先拿现在的金额跟之前添加的金额作比较
            //如果现在的金额大于之前的金额就拿现在的金额减去之前的金额得到刚增加的金额
            //如果现在的金额小于之前的金额就拿之前的金额减去现在的金额得到刚减去的金额
            //如果现在的跟之前的没变动就不用管
            if ($type == 1) {
                if ($xzbal != $zqbal) {
                    $bal = $xzbal > $zqbal ? $acc->balance + ($xzbal - $zqbal) : $acc->balance - ($zqbal - $xzbal);

                    Accounts::where('id', '=', $acc_id)->update(['balance' => $bal]);
                }
            } else {
                if ($xzbal != $zqbal) {
                    $bal = $xzbal > $zqbal ? $acc->balance - ($xzbal - $zqbal) : $acc->balance + ($zqbal - $xzbal);

                    Accounts::where('id', '=', $acc_id)->update(['balance' => $bal]);
                }
            }
        } else {
            //不相同 再判断现在的类型 1.总金额加上现在的 2.现在的金额加上之前的金额 再用总金额减去现在跟之前的金额
            $bal = $type == 1 ? $acc->balance + $xzbal : $acc->balance - ($xzbal + $zqbal);

            Accounts::where('id', '=', $acc_id)->update(['balance' => $bal]);
        }

    }

    public function del($id)
    {
        $yinc = Incomes::find($id);
        $inc = Incomes::where('id', '=', $id)->delete();
        if ($inc) {
            //首先判断是收入还是支出 收入：拿原来的金额减去现在的金额
            //支出：拿原来加上现在的
            $acc = Accounts::find($yinc->accounts_id);
            $bal = $yinc->is_types == 1 ? $acc->balance - $yinc->money : $acc->balance + $yinc->money;

            Accounts::where('id', '=', $yinc->accounts_id)->update(['balance' => $bal]);
            return response()->json(['success' => true,'msg'=>'删除成功']);
        } else {
            return response()->json(['success' => false,'msg'=>'删除失败！']);
        }
    }

    //报表
    public function report()
    {
        //查询所有的年
        $years = \DB::select("select distinct year(created_at)  year  from `incomes`");
        //dump($years);
        return view('incomes.report', compact('years'));
    }

    //年度收入\支出
    public function yearscs($year)
    {
        $ygsc=array();
        $ygzc=array();
        $user = Incomes::all()->toArray();
        foreach (array_unique(array_column($user, "staffs_id")) as $v) {
            //所有的成员收入统计
            $s=Incomes::where("staffs_id", "=", $v)->where('is_types','=',1);
            $name=Staffs::find($v)->name;
            if($s->count()>1){
                $mon = $s->pluck("money")->sum();
                //$name=Staffs::find($v)->name;
                if($mon != 0){
                    $ygsc[]=['mon'=>$mon,'name'=>$name];
                }
            }else{
                $mon=$s->pluck("money")->sum();
                ///$name=Staffs::find($v)->name;
                if ($mon != 0){
                    $ygsc[]=['mon'=>$mon,'name'=>$name];
                }
            }
            //所有成员支出统计
            $c=Incomes::where("staffs_id", "=", $v)->where('is_types','=',2);
            if($c->count()>1){
                $mon = $c->pluck("money")->sum();
                //$name=Staffs::find($v)->name;
                if($mon != 0){
                    $ygzc[]=['mon'=>$mon,'name'=>$name];
                }
            }else{
                $mon=$c->pluck("money")->sum();
                //$name=Staffs::find($v)->name;
                if ($mon != 0){
                    $ygzc[]=['mon'=>$mon,'name'=>$name];
                }
            }
        }
//        dump($ygsc);
//        $syygs =array();
//        collect(array_unique(array_column(Incomes::all()->toArray(), "staffs_id")))->map(function ($v) {
//            $s = Incomes::where("staffs_id", "=", $v);
//            if($s->count()>1){
//                dump($s->pluck("money")->sum());
//                dump(Staffs::find($s->first()->staffs_id)->name);
//                $syygs[] = array(['name' => Staffs::find($s->first()->staffs_id)->name,'mon' =>$s->pluck("money")->sum()]);
//            }else{
//                dump($s->pluck("money")->sum());
//                dump(Staffs::find($s->first()->staffs_id)->name);
//                $syygs[] = array(['name' => Staffs::find($s->first()->staffs_id)->name,'mon' =>$s->pluck("money")->sum()]);
//
//            }
//        });
//        dump($syygs);

        //年度的总收入跟支出
        $yearsr = \DB::select("SELECT SUM(money) mon FROM incomes WHERE year(created_at)='$year' AND is_types=1");
        $yearzc = \DB::select("SELECT SUM(money) mon FROM incomes WHERE year(created_at)='$year' AND is_types=2");

        $yearsc = array(['sr'=>$yearsr,'zc'=>$yearzc]);

        //根据年去查询一年的每个月的总收入\支出
        $incomes = array();
        for ($i=1; $i<=12; $i++){
            //dump($i);
            $ny = $i<10 ? $year.'-0'.$i : $year.'-'.$i;
            //收入
            $sr = \DB::select("select SUM(money) mon from  incomes where is_types=1 and date_format(created_at,'%Y-%m')='".$ny."'");
            //支出
            $zc = \DB::select("select SUM(money) mon from  incomes where is_types=2 and date_format(created_at,'%Y-%m')='".$ny."'");
            $incomes[]= ['sr' => $sr,'zc' =>$zc] ;
        }
        return response()->json(['success' => true,'incomes' => $incomes,'ygsc' => $ygsc,'ygzc' => $ygzc,'yearsc' => $yearsc]);
    }
}

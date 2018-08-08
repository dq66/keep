<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function index()
    {
        return view("index.index");
    }

    public function home(){
        //当前年的总收入跟总支出
        $year = date('Y');
        $enter = \DB::select("SELECT SUM(money) ent FROM incomes WHERE year(created_at)='$year' AND is_types=1");
        $come = \DB::select("SELECT SUM(money) com  FROM incomes WHERE year(created_at)='$year' AND is_types=2");

        //判断年收入跟年支出是否为空
        if(empty($enter) and empty($come)){
            $profit = array(['msg' => '年盈利\亏盈', 'data' => 0]);
        }else{
            if (!isset($enter[0]->ent)){
                $data = $come[0]->com;
                $profit = array(['msg' => '年亏盈', 'data' => $data]);
            }else{
                if($enter[0]->ent > $come[0]->com){
                    $data = $enter[0]->ent - $come[0]->com;
                    $profit = array(['msg' => '年盈利', 'data' => $data]);
                }else{
                    $data = $come[0]->com - $enter[0]->ent;
                    $profit = array(['msg' => '年亏盈', 'data' => $data]);
                }
            }
        }
        //基本信息
        //以下两条获取服务器时间，中国大陆采用的是东八区的时间,设置时区写成Etc/GMT-8
        date_default_timezone_set("Etc/GMT-8");
        $systemtime = date("Y-m-d H:i:s",time());
        $laravel = app();
        $mysql = \DB::select("select VERSION() my");
        $basic = array(
            'system' => $_SERVER["SERVER_SOFTWARE"],//运行环境
            'port' => $_SERVER['SERVER_PORT'],//端口
            'ym' => $_SERVER['SERVER_NAME'],//域名
            'edition' => $laravel::VERSION,//laravel版本
            'banben' => phpversion(),//php版本
            'mysql' => $mysql[0]->my,//mysql版本
            'systemtime' => $systemtime,//当前时间
        );


        ////        dump(json_decode(json_encode($enter),true));
        $enter = empty($enter) ? array(['da' => 0]) : array(['da' => $enter[0]->ent]);

        $come = empty($come) ? array(['da' => 0]) : array(['da' => $come[0]->com]);

        return view("index.home",compact('enter','come','profit','basic'));
    }

    //修改密码
    public function info(){
        return view("index.pwd");
    }
    public function pwd(Request $request){
        //dump($request->all());
        $admin = \Auth::attempt(['name' =>\Auth::user()->name,'password'=>$request->input('ypwd')]);
        if(!$admin){
            return Prompt($admin,'原密码错误！','Admin/info');
        }
        if($request->input('xpwd') != $request->input('qpwd')){
            return redirect('Admin/info')->withErrors('两次输入的密码不一致！');
        }

        $user = User::where('name',\Auth::user()->name)->update(['password'=>bcrypt($request->input('xpwd'))]);

        return Prompt($user,'修改密码','Admin/home');

    }

    //当前年收入\支出
    public function years(){

        $datetime = date('Y-m-s');
        $yes = explode('-',$datetime);
        //dump($yes[0]);
        $cmonths = array();
        $rmonths = array();
        for ($i=1; $i<=12; $i++){
            //dump($i);
            $ny = $i<10 ? $yes[0].'-0'.$i : $yes[0].'-'.$i;
            //收入
            $sr = \DB::select("select SUM(money) mon from  incomes where is_types=1 and date_format(created_at,'%Y-%m')='".$ny."'");
            //支出
            $zc = \DB::select("select SUM(money) mon from  incomes where is_types=2 and date_format(created_at,'%Y-%m')='".$ny."'");
            $rmonths[]= $sr;
            $cmonths[]= $zc;
        }
        //dump($months);
        return response()->json(['success' => true, 'rdata' => $rmonths,'cdata' =>$cmonths]);
    }

}

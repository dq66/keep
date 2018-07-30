<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use app\User;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index');
    }

    public function login(Request $request){
        //dump($request->post());
        $input = $request->except(['_token']);
        $admin = \Auth::attempt($input);
        if($admin){
            $ip = gethostbyname($_SERVER['SERVER_NAME']);
            User::where('name','=',\Auth::user()->name)->update(['lastlogin_ip'=>$ip]);
            return redirect('Admin');
        }else{
            return Prompt($admin,'用户名或密码错误','Admin/login/');
        }
    }

    public function logout(){
        \Auth::logout();
        return redirect('Admin/login');
    }
}

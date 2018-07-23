<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
//不需要验证
Route::group(['prefix' => 'Admin', 'namespace' => 'Admin'], function () {
    Route::group(['prefix' => 'login'], function () {
        //登录页面
        Route::get('/', 'LoginController@index');
        Route::post('login','LoginController@login');
        Route::get('logout','LoginController@logout');
    });
});

Route::group(['namespace' => 'Admin','prefix' => 'Admin','middleware'  => 'Usercheck'],function (){
    Route::get('/', 'AdminController@index');
    Route::get('index', 'AdminController@index');
    Route::get('home','AdminController@home');
    Route::get('statistics','AdminController@years');
    //修改管理员信息
    Route::get('info','AdminController@info');
    Route::post('pwd','AdminController@pwd');

    //收入\支出
    Route::group(['prefix' => 'Incomes'],function (){
        Route::get('/','IncomesController@index');
        Route::get('incomes_sel','IncomesController@sel');
        //添加
        Route::get('add','IncomesController@add');
        Route::post('create','IncomesController@create');
        //修改
        Route::get('edit/{id}','IncomesController@edit');
        Route::post('edit','IncomesController@edit');
        //删除
        Route::get('del/{id}','IncomesController@del');
        //报表
        Route::get('report','IncomesController@report');
        //年度每月收入\支出
        Route::get('yearscs/{year}','IncomesController@yearscs');
    });
    //应收\应付
    Route::group(['prefix' => 'Payments'],function (){
        Route::get('/','PaymentsController@index');
        Route::get('payments_sel','PaymentsController@sel');
        //添加
        Route::get('add','PaymentsController@add');
        Route::post('create','PaymentsController@create');
        //修改
        Route::get('edit/{id}','PaymentsController@edit');
        Route::post('edit','PaymentsController@edit');
        //删除
        Route::get('del/{id}','PaymentsController@del');
    });
    //账户
    Route::group(['prefix' => 'Accounts'], function(){
        Route::get('/','AccountsController@index');
        Route::get('prefix_sel','AccountsController@sel');
        //添加账户
        Route::post('create','AccountsController@create');
        //修改账户
        Route::get('edit/{id}','AccountsController@edit');
        Route::post('edit','AccountsController@edit');
        //删除类别
        Route::get('del/{id}','AccountsController@del');
        //资金管理
        Route::get('capital','AccountsController@capital');
        //收支流水详情
        Route::get('stream','AccountsController@stream');
        //转账（转入转账）
        Route::get('trans/{id}','AccountsController@stans');
    });
    //转账
    Route::group(['prefix' => 'Transfers'], function (){
        //转账流水详情
        Route::get('Transfers_sel','TransfersController@index');
        //转账
        Route::post('create','TransfersController@create');
    });
    //大类
    Route::group(['prefix' => 'Types'], function () {
        Route::get("/", 'TypesController@index');
        Route::get('types_sel','TypesController@sel');
        //ajax查询所有大类
        Route::get('ajaxtyps/{type}','TypesController@ajaxtypes');
        //ajax查询所有小类
        Route::get('xt/{ty}','TypesController@xt');
        //ajax查询大类下的小类
        Route::get('types_lx/{parent}','TypesController@types_xl');
        //添加类别
        Route::post("create", 'TypesController@create');
        //修改类别
        Route::get('edit/{id}','TypesController@edit');
        Route::post('edit','TypesController@edit');
        //删除类别
        Route::get('del/{id}','TypesController@del');
    });
    //小类
    Route::group(['prefix' => 'Xtypes'],function (){
        Route::get('/','XtypesController@index');
        Route::get('xtypes_sel','XtypesController@sel');
        //添加小类
        Route::post("create", 'XtypesController@create');
        //修改小类
        Route::get('edit/{id}','XtypesController@edit');
        Route::post('edit/','XtypesController@edit');
        //删除小类
        Route::get('del/{id}','XtypesController@del');
    });
    //客户
    Route::group(['prefix'=>'Customer'],function (){
        Route::get('/','CustomerController@index');
        Route::get('customer_sel','CustomerController@sel');
        //添加客户
        Route::post('create','CustomerController@create');
        //编辑客户
        Route::get('edit/{id}','CustomerController@edit');
        Route::post('edit','CustomerController@edit');
        //删除客户
        Route::get('del/{id}','CustomerController@del');
    });
    //员工
    Route::group(['prefix'=>'Staffs'],function (){
        Route::get('/','StaffsController@index');
        Route::get('staffs_sel','StaffsController@sel');
        //添加员工
        Route::post('create','StaffsController@create');
        //编辑员工
        Route::get('edit/{id}','StaffsController@edit');
        Route::post('edit','StaffsController@edit');
        //删除员工
        Route::get('del/{id}','StaffsController@del');
    });
    //项目
    Route::group(['prefix' => 'Projects'], function (){
        Route::get('/','ProjectsController@index');
        Route::get('projects_sel','ProjectsController@sel');
        //添加项目
        Route::post('create','ProjectsController@create');
        //编辑项目
        Route::get('edit/{id}','ProjectsController@edit');
        Route::post('edit','ProjectsController@edit');
        //删除项目
        Route::get('del/{id}','ProjectsController@del');
    });
});
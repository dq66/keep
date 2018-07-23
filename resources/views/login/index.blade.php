@extends("layouts.app")

@section("css")
    <link rel="stylesheet" type="text/css" href="/admin/css/login.css" />
@endsection()

@section("content")
<div class="m-login-bg">
    <div class="m-login">
        <h3>记账系统登录</h3>
        <div class="m-login-warp">
            <form method="post" action="login/login">
                <div class="layui-form-item">
                    <input type="text" name="name" required lay-verify="required" placeholder="用户名"
                           autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-item">
                    <input type="password" name="password" required lay-verify="required" placeholder="密码"
                           autocomplete="off" class="layui-input">
                </div>
                {{ csrf_field() }}
                <div class="layui-form-item m-login-btn">
                    <div class="layui-inline">
                        <button type="submit" style="width: 340px" class="layui-btn layui-btn-normal">登录</button>
                    </div>
                </div>
            </form>
        </div>
        <p class="copyright">Copyright 2015-2016 by XIAODU</p>
    </div>
</div>
@endsection()

@section("js")
@include("public.prompt")
@endsection()

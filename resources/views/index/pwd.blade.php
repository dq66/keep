@extends("layouts.app")

@section("content")
<div class="layui-tab page-content-wrap">
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <form method="post" action="pwd" class="layui-form" style="width: 90%;padding-top: 20px;">
                {{ csrf_field() }}
                <div class="layui-form-item">
                    <label class="layui-form-label">原密码：</label>
                    <div class="layui-input-block">
                        <input type="password" name="ypwd" required lay-verify="required" placeholder="请输入原密码" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">新密码：</label>
                    <div class="layui-input-block">
                        <input type="password" name="xpwd" required lay-verify="required" placeholder="请输入新密码" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">确认密码：</label>
                    <div class="layui-input-block">
                        <input type="password" name="qpwd" required lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn layui-btn-normal" type="submit">立即提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection()

@section("js")
    @include("public.prompt")
@endsection()

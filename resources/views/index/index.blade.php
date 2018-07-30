@extends("layouts.app")
@section("css")
    <link rel="stylesheet" type="text/css" href="/admin/css/admin.css"/>
@endsection()
@section("content")
<div class="main-layout" id='main-layout'>
    <!--侧边栏-->
    <div class="main-layout-side">
        <div class="m-logo">
        </div>
        <ul class="layui-nav layui-nav-tree" lay-filter="leftNav">
            <li class="layui-nav-item ">
                <a href="javascript:;"><i class="iconfont">&#xe607;</i>日常记账</a>
                <dl class="layui-nav-child">
                    <dd>
                        <a href="javascript:;" @can('Create In') data-url="/Admin/Incomes/add" @else class="qx" @endcan data-id='1' data-text="收入/支出记录">
                            <span class="l-line"></span>收入/支出记录
                        </a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-url="/Admin/Incomes" data-id='2' data-text="收支流水账">
                            <span class="l-line"></span>收支流水账
                        </a>
                    </dd>
                    <dd>
                        <a href="javascript:;" @can('Create Pa') data-url="/Admin/Payments/add" @else class="qx" @endcan data-id='3' data-text="应收/应付记录">
                            <span class="l-line"></span>应收/应付记录
                        </a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-url="/Admin/Payments" data-id='4' data-text="收付流水账">
                            <span class="l-line"></span>收付流水账
                        </a>
                    </dd>
                </dl>
            </li>
            <li class="layui-nav-item ">
                <a href="javascript:;"><i class="iconfont">&#xe600;</i>资金管理</a>
                <dl class="layui-nav-child">
                    <dd>
                        <a href="javascript:;" @can('Select Ac') data-url="/Admin/Accounts/capital" @else class="qx" @endcan data-id='5' data-text="资金账户余额">
                            <span class="l-line"></span>资金账户余额
                        </a>
                    </dd>
                </dl>
            </li>
            <li class="layui-nav-item ">
                <a href="javascript:;"><i class="iconfont">&#xe608;</i>报表</a>
                <dl class="layui-nav-child">
                    <dd>
                        <a href="javascript:;" data-url="/Admin/Incomes/report" data-id='6' data-text="收入/支出记录">
                            <span class="l-line"></span>收入/支出记录
                        </a>
                    </dd>
                </dl>
            </li>
            <li class="layui-nav-item ">
                <a href="javascript:;"><i class="iconfont">&#xe604;</i>管理</a>
                <dl class="layui-nav-child">
                    <dd>
                        <a href="javascript:;" data-url="/Admin/Accounts" data-id='7' data-text="账户管理">
                            <span class="l-line"></span>账户管理
                        </a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-url="/Admin/Types" data-id='8' data-text="大类管理">
                            <span class="l-line"></span>大类管理
                        </a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-url="/Admin/Xtypes" data-id='9' data-text="小类管理">
                            <span class="l-line"></span>小类管理
                        </a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-url="/Admin/Customer/" data-id='10' data-text="客户管理">
                            <span class="l-line"></span>客户管理
                        </a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-url="/Admin/Staffs/" data-id='11' data-text="员工管理">
                            <span class="l-line"></span>员工管理
                        </a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-url="/Admin/Projects" data-id='12' data-text="项目管理">
                            <span class="l-line"></span>项目管理
                        </a>
                    </dd>
                </dl>
            </li>
            @can('Administer roles & permissions')
                <li class="layui-nav-item ">
                <a href="javascript:;"><i class="iconfont">&#xe606;</i>管理员管理</a>
                <dl class="layui-nav-child">
                    <dd>
                        <a href="javascript:;" data-url="/Admin/Users" data-id='13' data-text="用户管理">
                            <span class="l-line"></span>用户管理
                        </a>
                    </dd>
                    @can('System')
                    <dd>
                        <a href="javascript:;" data-url="/Admin/Permissions" data-id='14' data-text="角色管理">
                            <span class="l-line"></span>角色管理
                        </a>
                    </dd>
                    <dd>
                        <a href="javascript:;" data-url="/Admin/Role" data-id='15' data-text="等级管理">
                            <span class="l-line"></span>等级管理
                        </a>
                    </dd>
                    @endcan
                </dl>
            </li>
            @endcan
        </ul>
    </div>
    <!--右侧内容-->
    <div class="main-layout-container">
        <!--头部-->
        <div class="main-layout-header">
            <div class="menu-btn" id="hideBtn">
                <a href="javascript:;">
                    <span class="iconfont">&#xe60e;</span>
                </a>
            </div>
            <ul class="layui-nav" lay-filter="rightNav">
                <li class="layui-nav-item">
                    <a href="javascript:;" data-url="/Admin/info" data-id='5' data-text="修改密码">{{Auth::user()->name}}  管理员</a>
                </li>
                <li class="layui-nav-item"><a href="/Admin/login/logout">退出</a></li>
            </ul>
        </div>
        <!--主体内容-->
        <div class="main-layout-body">
            <!--tab 切换-->
            <div class="layui-tab layui-tab-brief main-layout-tab" lay-filter="tab" lay-allowClose="true">
                <ul class="layui-tab-title">
                    <li class="layui-this welcome">后台主页</li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show" style="background: #f5f5f5;">
                        <!--1-->
                        <iframe src="/Admin/home" width="100%" height="100%" name="iframe" scrolling="auto" class="iframe" framborder="0"></iframe>
                        <!--1end-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--遮罩-->
    <div class="main-mask">

    </div>
</div>

@endsection()

@section("js")
<script type="text/javascript">
    var scope={
        link:'./welcome.html'
    }
</script>
@endsection()


@extends("layouts.app")
@section("css")
    <link rel="stylesheet" type="text/css" href="/admin/css/admin.css"/>
@endsection()
@section("content")
<div class="wrap-container welcome-container">
    <div class="row">
        <div class="welcome-left-container col-lg-9">
            <div class="data-show">
                <ul class="clearfix">
                    <li class="col-sm-12 col-md-4 col-xs-12">
                        <a href="javascript:;" class="clearfix">
                            <div class="icon-bg bg-org f-l">
                                <span class="iconfont">&#xe606;</span>
                            </div>
                            <div class="right-text-con">
                                <p class="name">年收入</p>
                                <p><span class="color-org">¥{{$enter[0]["da"]}}</span>数据
                                    <span class="iconfont">&#xe628;</span></p>
                            </div>
                        </a>
                    </li>
                    <li class="col-sm-12 col-md-4 col-xs-12">
                        <a href="javascript:;" class="clearfix">
                            <div class="icon-bg bg-blue f-l">
                                <span class="iconfont">&#xe602;</span>
                            </div>
                            <div class="right-text-con">
                                <p class="name">年支出</p>
                                <p><span class="color-blue">¥{{$come[0]["da"]}}</span>数据
                                    <span class="iconfont">&#xe628;</span></p>
                            </div>
                        </a>
                    </li>
                    <li class="col-sm-12 col-md-4 col-xs-12">
                        <a href="javascript:;" class="clearfix">
                            <div class="icon-bg bg-green f-l">
                                <span class="iconfont">&#xe605;</span>
                            </div>
                            <div class="right-text-con">
                                <p class="name">{{$profit[0]['msg']}}</p>
                                <p><span class="color-green">¥{{$profit[0]['data']}}</span>数据
                                    <span class="iconfont">
                                        @if($profit[0]['msg'] == "年盈利")
                                            &#xe628;
                                        @else
                                            &#xe60f;
                                        @endif
                                    </span></p>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            <!--图表-->
            <div class="chart-panel panel panel-default">
                <div class="panel-body" id="chart" style="height: 376px;">
                </div>
            </div>
            <!--服务器信息-->
            <div class="server-panel panel panel-default">
                <div class="panel-header">服务器信息</div>
                <div class="panel-body clearfix">
                    <div class="col-md-2">
                        <p class="title">运行环境</p>
                        <span class="info">{{$basic['system']}}</span>
                    </div>
                    <div class="col-md-2">
                        <p class="title">服务器域名/端口</p>
                        <span class="info">{{$basic['ym']}} [ {{$basic['port']}} ]</span>
                    </div>
                    <div class="col-md-2">
                        <p class="title">laravel版本</p>
                        <span class="info">{{$basic['edition']}}</span>
                    </div>
                    <div class="col-md-2">
                        <p class="title"> PHP版本</p>
                        <span class="info">{{$basic['banben']}}</span>
                    </div>
                    <div class="col-md-2">
                        <p class="title">数据库信息</p>
                        <span class="info">{{$basic['mysql']}}</span>
                    </div>
                    <div class="col-md-2">
                        <p class="title">服务器当前时间</p>
                        <span class="info">{{$basic['systemtime']}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection()

@section("js")
    <script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<script src="/admin/lib/echarts/echarts.js"></script>
<script type="text/javascript">
    var rdatas = [];
    var cdatas = [];
    var lrs = [];
    $.ajax({
        type:'get',
        url: '/Admin/statistics',
        dataType:'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        success:function (da) {
            //console.log(da);
            for ($i =0; $i<da['rdata'].length; $i++){
                //console.log(da['rdata'][$i][0]['mon']);
                //收入
                if(da['rdata'][$i][0]['mon'] == null){
                    rdatas.push(0);
                }else {
                    rdatas.push(da['rdata'][$i][0]['mon']);
                }
                //支出
                if(da['cdata'][$i][0]['mon'] == null){
                    cdatas.push(0);
                }else {
                    cdatas.push(da['cdata'][$i][0]['mon']);
                }
                //利润
                if(da['rdata'][$i][0]['mon'] == null && da['cdata'][$i][0]['mon'] == null){
                    lrs.push(0);
                }else if(da['rdata'][$i][0]['mon'] == null){
                    lrs.push(-da['cdata'][$i][0]['mon']);
                }else if(da['cdata'][$i][0]['mon'] == null){
                    lrs.push(da['rdata'][$i][0]['mon']);
                }else{
                    var a = da['rdata'][$i][0]['mon'] - da['cdata'][$i][0]['mon'];
                    lrs.push(a);
                }
            }
            //console.log(cdatas);
            //console.log(rdatas);
            //console.log(lrs);
            layui.use(['layer','jquery'], function(){
                var layer 	= layui.layer;
                var $=layui.jquery;
                //图表
                var myChart;
                require.config({
                    paths: {
                        echarts: '/admin/lib/echarts'
                    }
                });
                require(
                    [
                        'echarts',
                        'echarts/chart/bar',
                        'echarts/chart/line',
                        'echarts/chart/map'
                    ],
                    function (ec) {
                        //--- 折柱 ---
                        myChart = ec.init(document.getElementById('chart'));
                        myChart.setOption(
                            {
                                title: {
                                    text: "月收入/支出统计",
                                    textStyle: {
                                        color: "rgb(85, 85, 85)",
                                        fontSize: 18,
                                        fontStyle: "normal",
                                        fontWeight: "normal"
                                    }
                                },
                                tooltip: {
                                    trigger: "axis"
                                },
                                legend: {
                                    data: ["收入", "支出", "利润"],
                                    selectedMode: false,
                                },
                                toolbox: {
                                    show: true,
                                    feature: {
                                        dataView: {
                                            show: false,
                                            readOnly: true
                                        },
                                        magicType: {
                                            show: false,
                                            type: ["line", "bar", "stack", "tiled"]
                                        },
                                        restore: {
                                            show: true
                                        },
                                        saveAsImage: {
                                            show: true
                                        },
                                        mark: {
                                            show: false
                                        }
                                    }
                                },
                                calculable: false,
                                xAxis: [
                                    {
                                        type: "category",
                                        boundaryGap: false,
                                        data: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月']
                                    }
                                ],
                                yAxis: [
                                    {
                                        type: "value"
                                    }
                                ],
                                grid: {
                                    x2: 30,
                                    x: 80
                                },
                                series: [
                                    {
                                        name: "收入",
                                        type: "line",
                                        smooth: true,
                                        itemStyle: {
                                            normal: {
                                                areaStyle: {
                                                    type: "default"
                                                }
                                            }
                                        },
                                        data:rdatas
                                    },
                                    {
                                        name: "支出",
                                        type: "line",
                                        smooth: true,
                                        itemStyle: {
                                            normal: {
                                                areaStyle: {
                                                    type: "default"
                                                }
                                            }
                                        },
                                        data:cdatas
                                    },
                                    {
                                        name: "利润",
                                        type: "line",
                                        smooth: true,
                                        itemStyle: {
                                            normal: {
                                                areaStyle: {
                                                    type: "default"
                                                },
                                                color: "rgb(110, 211, 199)"
                                            }
                                        },
                                        // data: [1320, 1132, 601, 234, 120, 90, 20]
                                        data:lrs
                                    }
                                ]
                            }
                        );
                    }
                );
                $(window).resize(function(){
                    myChart.resize();
                })
            });
        }
    });

</script>
@endsection()

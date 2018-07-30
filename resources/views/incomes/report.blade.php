@extends("layouts.app")
@section("css")
    <link rel="stylesheet" type="text/css" href="/admin/css/admin.css"/>
@endsection()
@section("content")
    <div class="wrap-container welcome-container" style="background-color: #FCFCFC">
        <div class="row">
            <div class="welcome-left-container col-lg-99">
                <div style="height: 40px">
                    <select name="year" class="layui-select year" style="width: 31.6%;margin-left: 15px">
                        @foreach($years as $ye)
                            <option value="{{$ye->year}}">{{$ye->year}}年</option>
                        @endforeach
                    </select>
                </div>
                <div class="data-show">
                    <ul class="clearfix">
                        <li class="col-sm-12 col-md-4 col-xs-122">
                            <a href="javascript:;" class="clearfix">
                                <div id="op" style="height: 300px;"></div>
                            </a>
                        </li>
                        <li class="col-sm-12 col-md-4 col-xs-122">
                            <a href="javascript:;" class="clearfix">
                                <div id="op1" style="height: 300px;"></div>
                            </a>
                        </li>
                        <li class="col-sm-12 col-md-4 col-xs-122">
                            <a href="javascript:;" class="clearfix">
                                <div id="op2" style="height: 300px;"></div>
                            </a>
                        </li>
                    </ul>
                </div>
                <!--图表-->
                <div class="chart-panel panel panel-default">
                    <div class="panel-body" id="chart" style="height: 376px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection()

@section("js")
    <script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
    <script src="/admin/js/echarts.common.min.js"></script>
    <script type="text/javascript">
        var year = $('.year option:selected').val();
        var srdata = [];
        var zcdata = [];
        var ygsr = [];
        var ygnames  = [];
        var ygzc = [];
        var ygzcnames = [];
        var yearsc = [];
        $.ajax({
            type:'get',
            url:'/Admin/Incomes/yearscs/'+year,
            dataType:'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success:function (da) {
                //console.log(da);
                // console.log(da['incomes']);
                //每年每个月的收入\支出的总数
                for ($i =0 ;$i<da['incomes'].length;$i++){
                    //收入
                    if(da['incomes'][$i]['sr'][0]['mon'] == null){
                        srdata.push(0);
                    }else {
                        srdata.push(da['incomes'][$i]['sr'][0]['mon']);
                    }
                    //支出
                    if(da['incomes'][$i]['zc'][0]['mon'] == null){
                        zcdata.push(0);
                    }else {
                        zcdata.push(da['incomes'][$i]['zc'][0]['mon']);
                    }
                }
                //所有的员工收入
                for($i=0;$i<da['ygsc'].length;$i++){
                    //console.log(da['ygsc'][$i]['name']);
                    var name = da['ygsc'][$i]['name'];
                    var value = da['ygsc'][$i]['mon'];
                    ygsr.push({name: name, value: value});
                    ygnames.push(name);
                }
                //所有的员工支出
                for ($i=0;$i<da['ygzc'].length;$i++){
                    var name = da['ygzc'][$i]['name'];
                    var value = da['ygzc'][$i]['mon'];
                    ygzc.push({name:name,value: value});
                    ygzcnames.push(name);
                }
                //每年的总收入跟支出
                console.log(da['yearsc']);
                var sr =da['yearsc'][0]['sr'][0]['mon'];
                var zc =da['yearsc'][0]['zc'][0]['mon'];
                yearsc.push({name:'收入',value:sr},{name:'支出',value:zc});

                // console.log(srdata);
                // console.log(zcdata);
                let myChart = echarts.init(document.getElementById('chart'));
                let myChart1 = echarts.init(document.getElementById('op'));
                let myChart2 = echarts.init(document.getElementById('op1'));
                let myChart3 = echarts.init(document.getElementById('op2'));
                let option = {
                    title: {
                        text: "收入 / 支出",
                            textStyle: {
                                color: "rgb(85, 85, 85)",
                                fontSize: 18,
                                fontStyle: "normal",
                                fontWeight: "normal"
                            }
                    },
                    tooltip : {
                        trigger: 'axis',
                    },
                    legend: {
                        data:['收入','支出']
                    },
                    toolbox: {
                        show : true,
                        feature : {
                            dataView : {show: true, readOnly: false},
                            magicType : {show: true, type: ['line', 'bar']},
                            restore : {show: true},
                            saveAsImage : {show: true}
                        }
                    },
                    calculable : true,
                    xAxis : [{
                        type : 'category',
                        data : ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'],
                        // x轴的字体样式
                        axisLabel: {
                            show: true,    //这行代码控制着坐标轴x轴的文字是否显示
                            textStyle: {
                                color: '#454545',   //x轴上的字体颜色
                                fontSize:'12'    // x轴字体大小
                            }
                        },
                        axisLine:{
                            lineStyle:{
                                color:'#4682B4', // x坐标轴的轴线颜色
                                width:2,      //这里是坐标轴的宽度,可以去掉
                            }
                        },
                        axisPointer: {
                            type: 'shadow'
                        }
                    }],
                    yAxis : [{
                        type : 'value',
                        axisTick: {show: false},// 去除坐标轴上的刻度线
                        axisLabel: {
                            show: true,    //这行代码控制着坐标轴x轴的文字是否显示
                            textStyle: {
                                color: '#454545',   //x轴上的字体颜色
                                fontSize:'12'    // x轴字体大小
                            }
                        },
                        axisLine:{
                            lineStyle:{
                                color:'#4682B4', // x坐标轴的轴线颜色
                                width:2,      //这里是坐标轴的宽度,可以去掉
                            }
                        },
                    }],
                    grid: { x2: 30,x: 50},
                    series : [{
                        name:'收入',
                        type:'bar',
                        itemStyle: {
                            normal: {
                                areaStyle: {
                                    type: "default"
                                },
                                color: "#FF8247"
                            }
                        },
                        data:srdata
                    },{
                        name:'支出',
                        type:'bar',
                        itemStyle: {
                            normal: {
                                areaStyle: {
                                    type: "default"
                                },
                                color: "#87CEFA"
                            }
                        },
                        data:zcdata
                    }]
                };
                let option1 = {
                    title: {
                        text: '所有成员收入统计',
                        x: 'center',
                    },
                    tooltip: {
                        trigger: 'item',
                        formatter: "{a} <br/>{b} : {c} ({d}%)"
                    },
                    legend: {
                        bottom: 10,
                        left: 'center',
                        // data: ['直接访问', '邮件营销', '联盟广告', '视频广告', '搜索引擎']
                        data:ygnames
                    },
                    toolbox: {
                        show: true,
                        feature: {
                            mark: {show: true},
                            dataView: {show: true, readOnly: false},
                            magicType: {
                                show: true,
                                type: ['pie', 'funnel']
                            },
                            restore: {show: true},
                            saveAsImage: {show: true}
                        }
                    },
                    calculable: true,
                    color:['#FF8247','#87CEFA','#9af4dc','#c283ee','#6be18e','#4d88e8','#e86fdf'],//自己设置扇形图颜色
                    series: [{
                        name: '访问来源',
                        type: 'pie',
                        radius: '55%',
                        center: ['50%', '50%'],
                        data:ygsr,
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }]
                };
                let option2 = {
                    title:{
                        text:'年收入/支出统计',
                        x:'center'
                    },
                    tooltip: {
                        trigger: 'item',
                        formatter: "{a} <br/>{b}: {c}%"
                    },
                    legend: {
                        bottom: 10,
                        left: 'center',
                        data:['收入','支出']
                    },
                    toolbox: {
                        show : true,
                        feature : {
                            mark : {show: true},
                            dataView : {show: true, readOnly: false},
                            magicType : {
                                show: true,
                                type: ['pie', 'funnel']
                            },
                            restore : {show: true},
                            saveAsImage : {show: true}
                        }
                    },
                    calculable : true,
                    color:['#FF8247','#87CEFA'],
                    series: [
                        {
                            name:'收入/支出',
                            type:'pie',
                            radius: ['40%', '55%'],
                            avoidLabelOverlap: false,
                            label: {
                                normal: {
                                    show: false,
                                    position: 'center'
                                },
                                emphasis: {
                                    show: true,
                                    textStyle: {
                                        fontSize: '20',
                                        fontWeight: 'bold'
                                    }
                                }
                            },
                            labelLine: {
                                normal: {
                                    show: false
                                }
                            },
                            data:yearsc
                        }
                    ]
                };
                let option3 = {
                    title : {
                        text: '所有成员支出统计',
                        x:'center'
                    },
                    tooltip : {
                        trigger: 'item',
                        formatter: "{a} <br/>{b} : {c} ({d}%)"
                    },
                    legend: {
                        orient: 'vertical',
                        x: 'left',
                        data:ygzcnames
                    },
                    toolbox: {
                        show : true,
                        feature : {
                            mark : {show: true},
                            dataView : {show: true, readOnly: false},
                            magicType : {
                                show: true,
                                type: ['pie', 'funnel']
                            },
                            restore : {show: true},
                            saveAsImage : {show: true}
                        }
                    },
                    calculable : true,
                    color:['#FF8247','#87CEFA','#9af4dc','#c283ee','#6be18e','#4d88e8','#e86fdf'],//自己设置扇形图颜色
                    series : [
                        {
                            name:'面积模式',
                            type:'pie',
                            radius : [30, 100],
                            center : ['50%', '55%'],
                            roseType : 'area',
                            label: {
                                normal: {
                                    show: false
                                },
                                emphasis: {
                                    show: false
                                }
                            },
                            data:ygzc
                        }
                    ]
                };
                myChart.setOption(option);
                myChart1.setOption(option1);
                myChart2.setOption(option2);
                myChart3.setOption(option3);
            }
        });
    </script>
@endsection()

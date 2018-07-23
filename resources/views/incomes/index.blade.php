@extends("layouts.app")
@section("css")
    @include("public.css")
    <style>
        .layui-btn-small {
            height: 33px!important;
        }
        .sx{
            height: 35px!important;
            margin-left: 10px;
            padding: 0px 10px;
            cursor: pointer;
            border: 1px solid #ccc;
        }
        .yk{
            color: #FF5722;
        }
        .sr{
            color: #f8bb74;
        }
        .zc{
            color: #86d2ee;
        }
        .form-control {
            width: 95%!important;
        }
    </style>
@endsection()
@section("content")
    <table data-toggle="table" id="table">
        <div id="toolbar" style="display: flex">
            <button class="layui-btn layui-btn-small layui-btn-normal gjsearch" data-toggle="modal" data-target="#myModal008">高级查询</button>
            <select id="sel_exportoption" lay-filter="selecrex" class="selecrex">
                <option value="basic">导出当前页面数据</option>
                <option value="all">导出全部数据</option>
                <option value="selected">导出选中数据</option>
            </select>
            <div class="sx">
                <span class="yk">{{$profit[0]['msg']}}:￥{{$profit[0]['data']}}</span>
                <span class="sr">收入:￥{{$enter[0]->ent}}</span>
                <span class="zc">支出:￥{{$come[0]->com}}</span>
            </div>
        </div>
    </table>
    <div class="modal fade" id="myModal008" onclick="all()" tabindex="-1" role="dialog" aria-labelledby="myModalLabels">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="layui-layer-title" style="cursor: move;">高级查询</div>
                <div class="modal-body">
                    <div class="layui-tab-item layui-show">
                        <div class="layui-form-item">
                            <label class="layui-form-label">类型：</label>
                            <div class="layui-input-block">
                                <select id="is_types" class="layui-input is_types">
                                    <option value="all" selected>所有类型</option>
                                    <option value="1">收入</option>
                                    <option value="2">支出</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">所属大类：</label>
                            <div class="layui-input-block">
                                <select name="parent"  class="layui-input types">
                                    <option value="all">所有大类</option>
                                    @foreach($types as $ty)
                                        <option value="{{$ty->id}}">{{$ty->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">所属小类：</label>
                            <div class="layui-input-block">
                                <select name="parent"  class="layui-input xtype">
                                    <option value="all">所有小型</option>
                                    @foreach($xtype as $xl)
                                        <option value="{{$xl->id}}">{{$xl->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">开始时间：</label>
                            <div class="layui-input-block">
                                <input type="date" class="layui-input starttime" placeholder="请选择开始日期">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">结束时间：</label>
                            <div class="layui-input-block">
                                <input type="date" placeholder="请选择结束日期" class="layui-input jstime">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">资金账户：</label>
                            <div class="layui-input-block">
                                <select name="parent" class="layui-input accounts">
                                    <option value="all">所有账户</option>
                                    @foreach($accounts as $ac)
                                        <option value="{{$ac->id}}">{{$ac->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">生意伙伴：</label>
                            <div class="layui-input-block">
                                <select name="parent" class="layui-input customer">
                                    <option value="all">所有生意伙伴</option>
                                    @foreach($customer as $cu)
                                        <option value="{{$cu->id}}">{{$cu->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">业务项目：</label>
                            <div class="layui-input-block">
                                <select name="parent" class="layui-input projects">
                                    <option value="all">所有项目</option>
                                    @foreach($projects as $pr)
                                        <option value="{{$pr->id}}">{{$pr->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">记账人：</label>
                            <div class="layui-input-block">
                                <select name="parent" class="layui-input users">
                                    <option value="all">所有记账人</option>
                                    @foreach($users as $us)
                                        <option value="{{$us->id}}">{{$us->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary searchdata"><i class="iconfont icon-gaojisousuo"></i>
                        查询
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection()

@section("js")
    @include("public.prompt")
    <script>
        var $table = $('#table');
        $table.bootstrapTable({
            url: '/Admin/Incomes/incomes_sel',              //请求后台的URL（*）
            method: 'get',               //请求方式（*）
            toolbar: '#toolbar',         //工具按钮用哪个容器
            pagination: true,            //是否显示分页（*）
            cache: true,                 //设置为 false 禁用 AJAX 数据缓存。
            showColumns: true,           //是否显示所有的列（选择显示的列）
            search:true,                 //是否显示搜索框
            trimOnSearch:true,           //设置为 true 将自动去掉搜索字符的前后空格。
            contentType: "application/x-www-form-urlencoded", /**支持跨域**/
            clickToSelect: true,        //设置 true 将在点击行时，自动选择 rediobox 和 checkbox。
            striped: true,              //设置为 true 会有隔行变色效果
            showRefresh: true,          //是否显示刷新按钮
            pageList: [5, 10, 15, 20],  //如果设置了分页，设置可供选择的页面数据条数。设置为 All 或者 Unlimited，则显示所有记录。
            pageNumber: 1,              //初始化加载第一页，默认第一页
            pageSize: 5,                //每页的记录行数（*）
            sortable: true,             //是否启用排序
            sortOrder: "desc",           //排序方式
            showExport: true,           //显示导出按钮
            exportDataType: "basic",     //basic', 'all', 'selected'.
            exportTypes: ['excel', 'PDF', 'PNG'],
            exportOptions: {
                ignoreColumn: [0,11],  //忽略某一列的索引
                fileName: '支出流水账',  //文件名称设置
                worksheetName: 'sheet1',  //表格工作区名称
                excelstyles: ['background-color', 'color', 'font-size', 'font-weight'],
                onMsoNumberFormat: $.DoOnMsoNumberFormat
            },
            sidePagination: "server", //服务端处理分页
            columns: [{
                checkbox: true,
                visible: true                  //是否显示复选框
            },{
                title:"ID",
                field: 'id',
                align: 'center',
                valign: 'middle',
                sortable: true,//排序
                visible:false
            },{
                title:"小类",
                field: 'parent_id',
                align: 'center',
                valign: 'middle',
                visible:false
            },{
                title: '[类型]大类->小类',
                field: 'types_id',
                align: 'center',
                valign: 'middle',
                formatter:function (value,row) {
                    var dl = row.types_id;
                    var xl = row.parent_id;
                    return dl+" -> "+xl;
                }
            },{
                title: '收入',
                field: 'money',
                align: 'center',
                valign: 'middle',
                sortable: true,
                formatter: function (value, row) {
                    var type = row.is_types;
                    if (type == 1) {
                        return value
                    }
                }
            },{
                title: '支出',
                field: 'money',
                align: 'center',
                valign: 'middle',
                sortable: true,
                formatter: function (value, row) {
                    var type = row.is_types;
                    if (type == 2) {
                        return value
                    }
                }
            },{
                title: '所属账户',
                field: 'accounts_id',
                align: 'center',
                valign: 'middle',
            },{
                title: '生意伙伴',
                field: 'customers_id',
                align: 'center',
                valign: 'middle',
            },{
                title: '业务项目',
                field: 'projects_id',
                align: 'center',
                valign: 'middle',
            },{
                title: '经手人',
                field: 'staffs_id',
                align: 'center',
                valign: 'middle',
            },{
                title: '记账人',
                field: 'users_id',
                align: 'center',
                valign: 'middle',
            },{
                title: '备注',
                field: 'desc',
                align: 'center',
                valign: 'middle',
            },{
                title: '创建日期',
                field: 'created_at',
                align: 'center',
                valign: 'middle',
                sortable: true,//排序
            }, {
                title: "操作",
                align: 'center',
                valign: 'middle',
                width: 160, // 定义列的宽度，单位为像素px
                formatter: function (value,row) {
                    var id = row.id;
                    return `<button class="layui-btn layui-btn-mini layui-btn-normal" onclick="location.href='Incomes/edit/${id}'"><i class="layui-icon"></i></button><button class="layui-btn layui-btn-mini layui-btn-danger del-btn" data-id="${id}" data-name="${id}" data-url="Incomes"><i class="layui-icon"></i></button>`
                }
            }
            ],responseHandler: function (res) {
                return {
                    "total": res.total,//总页数
                    "rows": res.data   //数据
                };
            },
        });
        $(function () {
            // 选择导出 all selected basic
            $('.selecrex').change(function () {
                var exportoption = $(this).find('option:selected').val();
                //console.log(exportoption);
                $('#table').bootstrapTable('refreshOptions', {
                    exportDataType: exportoption
                });
            });
            //类型变动大类
            $('.is_types').change(function () {
                var is_type = $(this).find('option:selected').val();
                //console.log(is_type);
                dl(is_type);
            });
            //大类变动小类
            $('.types').change(function () {
                var parent = $(this).find('option:selected').val();
                xl(parent);
            });

            function dl($lx) {//大类
                $('.types option').remove();
                $.ajax({
                    type:'get',
                    url:'Types/ajaxtyps/'+$lx,
                    dataType:'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    success:function (da) {
                        //console.log(da);
                        var html = '';
                        for ($i=0;$i<da['data'].length;$i++){
                            html += `<option value="${da['data'][$i].id}">${da['data'][$i].name}</option>`;
                        }
                        $('.types').html(html);
                    }
                });
            }

            function xl($parent){//小类
                $('.xtype option').remove();
                $.ajax({
                    type:'get',
                    url:'Types/types_lx/'+$parent,
                    dataType:'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    success:function (da) {
                        //console.log(da);
                        var html = '';
                        if(da['data'].length == 0){
                            html += `<option value="all">所有小类</option>`;
                        }else{
                            for ($i=0;$i<da['data'].length;$i++){
                                html += `<option value="${da['data'][$i].id}">${da['data'][$i].name}</option>`;
                            }
                        }

                        $('.xtype').html(html);
                    }
                });
            }

            $('.searchdata').click(function () {
                $is_types = $('.is_types').find('option:selected').val(); // 收支类型
                $types_id = $('.types').find('option:selected').val();// 大类
                $xtype_id = $('.xtype').find('option:selected').val();// 小类
                $accounts_id = $('.accounts').find('option:selected').val();// 账户
                $customer_id = $('.customer').find('option:selected').val();// 生意伙伴
                $projects_id = $('.projects').find('option:selected').val();// 业务项目
                $users_id = $('.users').find('option:selected').val();// 经办人
                $starttime = $('.starttime').val(); // 开始时间
                $endtime = $('.jstime').val(); // 结束时间

                $("#table").bootstrapTable('refresh', {
                    url: '/Admin/Incomes/incomes_sel',
                    method: 'get',
                    query:{
                        is_types:$is_types,
                        types_id:$types_id,
                        parent_id :$xtype_id,
                        accounts_id:$accounts_id,
                        customer_id:$customer_id,
                        projects_id:$projects_id,
                        users_id:$users_id,
                        starttime:$starttime,
                        endtime:$endtime,
                    }
                });
                $('#myModal008').modal('hide');
            });
        });
    </script>
@endsection()
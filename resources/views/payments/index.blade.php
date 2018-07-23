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
            color: #1E9FFF;
        }
        .zc{
            color: #f8bb74;
        }
        .form-control {
            width: 95%!important;
        }
        #sel_exportoption{
            margin-left: 0px!important;
        }
    </style>
@endsection()
@section("content")
    <table data-toggle="table" id="table">
        <div id="toolbar" style="display: flex">
            <select id="sel_exportoption" lay-filter="selecrex" class="selecrex">
                <option value="basic">导出当前页面数据</option>
                <option value="all">导出全部数据</option>
                <option value="selected">导出选中数据</option>
            </select>
            <div class="sx">
                <span class="yk">{{$profit[0]['msg']}}:￥{{$profit[0]['data']}}</span>
                <span class="sr">应收:￥{{$enter[0]->ent}}</span>
                <span class="zc">应付:￥{{$come[0]->com}}</span>
            </div>
        </div>
    </table>
@endsection()

@section("js")
    @include("public.prompt")
    <script>
        var $table = $('#table');
        $table.bootstrapTable({
            url: '/Admin/Payments/payments_sel',              //请求后台的URL（*）
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
                ignoreColumn: [0,10],  //忽略某一列的索引
                fileName: '收付流水账',  //文件名称设置
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
                field: 'types',
                align: 'center',
                valign: 'middle',
                formatter:function (value,row) {
                    var dl = row.types_id;
                    var xl = row.parent_id;
                    return dl+" -> "+xl;
                }
            },{
                title: '应收',
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
                title: '应付',
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
                title: '期限',
                field: 'term',
                align: 'center',
                valign: 'middle',
            },{
                title: '备注',
                field: 'desc',
                align: 'center',
                valign: 'middle',
            },{
                title: '时间',
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
                    return `<button class="layui-btn layui-btn-mini layui-btn-normal" onclick="location.href='Payments/edit/${id}'"><i class="layui-icon"></i></button><button class="layui-btn layui-btn-mini layui-btn-danger del-btn" data-id="${id}" data-name="${id}" data-url="Payments"><i class="layui-icon"></i></button>`
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

            $('.searchdata').click(function () {
                types_id = $('.types_id').find('option:selected').val(); // 收支类型
                types_id = $('.types').find('option:selected').val();// 大类
                xtype_id = $('.xtype').find('option:selected').val();// 小类
                accounts_id = $('.accounts').find('option:selected').val();// 账户
                customer_id = $('.customer').find('option:selected').val();// 生意伙伴
                projects_id = $('.projects').find('option:selected').val();// 业务项目
                users_id = $('.users').find('option:selected').val();// 经办人
                starttime = $('.starttime').val(); // 开始时间
                endtime = $('.endtime').val(); // 结束时间
                $("#table").bootstrapTable('refresh', {url: '/Admin/Incomes/incomes_sel'});
                $('#myModal008').modal('hide');
            });
        });
    </script>
@endsection()

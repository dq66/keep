@extends("layouts.app")
@section("css")
    @include("public.css")
@endsection()
@section("content")
    <table data-toggle="table" id="table"></table>
    <!-- Modal 收支流水 -->
    <div class="modal fade" id="myModalzc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel181">收支流水详情</h4>
                </div>
                <div class="modal-body">
                    <table data-toggle="table" id="table2">
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal 转账流水 -->
    <div class="modal fade" id="myModalzz" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel190">转账流水详情</h4>
                </div>
                <div class="modal-body">
                    <table data-toggle="table" id="table3">
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
    <!--转账-->
    <div style="display: none" class="add">
        <form method="post" action="/Admin/Transfers/create" class="layui-form column-content-detail">
            <div class="layui-tab-item layui-show">
                <div class="layui-form-item">
                    <label class="layui-form-label">转出账户：</label>
                    <div class="layui-input-block">
                        <input type="text" style="color: red" readonly class="layui-input zc">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">转入账户：</label>
                    <div class="layui-input-block">
                        <select name="accounts_id" id="acc" class="layui-input">
                            <option value="">请选择</option>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">转出余额：</label>
                    <div class="layui-input-block">
                        <input type="text" name="money" required lay-verify="required" placeholder="请输入余额"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">转账备注：</label>
                    <div class="layui-input-block">
                        <textarea name="desc" class="layui-textarea"></textarea>
                    </div>
                </div>
            </div>
            {{ csrf_field() }}
            <input type="hidden" name="turn_out" id="turn_out">
            <div class="layui-form-item" style="padding-left: 10px;">
                <div class="layui-input-block">
                    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formDemo">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
@endsection()

@section("js")
    @include("public.prompt")
    <script>
        var $table = $('#table');
        $table.bootstrapTable({
            url: '/Admin/Accounts/prefix_sel',              //请求后台的URL（*）
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
            sortOrder: "asc",           //排序方式
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
                title: '账户名称',
                field: 'name',
                align: 'center',
                valign: 'middle',
            },{
                title: '当前余额',
                field: 'balance',
                align: 'center',
                valign: 'middle',
                sortable: true,//排序
            },{
                title: "对账/明细",
                align: 'center',
                valign: 'middle',
                formatter: function (value,row) {
                    var id = row.id;
                    var name = row.name;
                        return `<button class="layui-btn layui-btn-mini layui-btn-normal" onclick="exportszls('${id}')">收支流水详情</button><button class="layui-btn layui-btn-mini layui-btn-normal" onclick="yhzzzzls('${id}')">转账流水详情</button><button class="layui-btn layui-btn-mini layui-btn-normal tra_add" data-id="${id}" data-name="${name}" data-url="add">转账</button>`

                }
            }
            ],responseHandler: function (res) {
                return {
                    "total": res.total,//总页数
                    "rows": res.data   //数据
                };
            },
        });
        $('#table2').bootstrapTable({
            url: '',         //请求后台的URL（*）
            method: 'get',             //请求方式（*）
            toolbar: '#toolbar',        //工具按钮用哪个容器
            pagination: true,                   //是否显示分页（*）
            cache: true,
            showColumns: true,
            striped: true,
            ajaxOptions: {},
            contentType: "application/x-www-form-urlencoded", /**支持跨域**/
            clickToSelect: true,
            showRefresh: true,                  //是否显示刷新按钮
            pageList: [5, 10, 15, 20],
            pageNumber: 1,                       //初始化加载第一页，默认第一页
            pageSize: 5, //每页的记录行数（*）
            showExport: true,//显示导出按钮
            exportDataType: "all", //basic', 'all', 'selected'.
            exportTypes: ['excel', 'PDF', 'PNG'],
            exportOptions: {
                fileName: '收支流水',  //文件名称设置
                worksheetName: 'sheet1',  //表格工作区名称
                excelstyles: ['background-color', 'color', 'font-size', 'font-weight'],
                onMsoNumberFormat: $.DoOnMsoNumberFormat
            },
            sidePagination: "client", //服务端处理分页
            columns: [
                {
                    title: 'ID',
                    field: 'id',
                    align: 'center',
                    valign: 'middle',
                    visible: false
                },{
                    title: '类型',
                    field: 'is_types',
                    align: 'center',
                    formatter: function (value, row) {
                        if (row.is_types == 1) {
                            return "收入"
                        }else{
                            return "支出"
                        }
                    }
                },{
                    title: '成员',
                    field: 'users_id',
                    align: 'center',
                },{
                    title: '帐户',
                    field: 'accounts_id',
                    align: 'center',
                },{
                    title: '流入',
                    field: 'money',
                    align: 'center',
                    sortable: true,
                    formatter: function (value, row) {
                        if (row.is_types == 1) {
                            return value
                        }
                    }
                },{
                    title: '流出',
                    field: 'money',
                    align: 'center',
                    sortable: true,
                    formatter: function (value, row) {
                        if (row.is_types == 2) {
                            return value
                        }
                    }
                },{
                    title: '备注',
                    field: 'desc',
                    align: 'center',
                },{
                    title: '时间',
                    field: 'created_at',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                }
            ],
        });
        $('#table3').bootstrapTable({
            url: '',         //请求后台的URL（*）
            method: 'get',             //请求方式（*）
            toolbar: '#toolbar',        //工具按钮用哪个容器
            pagination: true,                   //是否显示分页（*）
            cache: true,
            showColumns: true,
            striped: true,
            ajaxOptions: {},
            contentType: "application/x-www-form-urlencoded", /**支持跨域**/
            clickToSelect: true,
            showRefresh: true,                  //是否显示刷新按钮
            pageList: [5, 10, 15, 20],
            pageNumber: 1,                       //初始化加载第一页，默认第一页
            pageSize: 5, //每页的记录行数（*）
            showExport: true,//显示导出按钮
            exportDataType: "all", //basic', 'all', 'selected'.
            exportTypes: ['excel', 'PDF', 'PNG'],
            exportOptions: {
                fileName: '转账流水',  //文件名称设置
                worksheetName: 'sheet1',  //表格工作区名称
                excelstyles: ['background-color', 'color', 'font-size', 'font-weight'],
                onMsoNumberFormat: $.DoOnMsoNumberFormat
            },
            sidePagination: "client", //服务端处理分页
            columns: [
                {
                    title: 'ID',
                    field: 'id',
                    align: 'center',
                    valign: 'middle',
                    visible: false
                },{
                    title: '类型',
                    field: 'is_types',
                    align: 'center',
                    formatter: function (value, row) {
                        if (row.is_types == 1) {
                            return "转入"
                        }else{
                            return "转出"
                        }
                    }
                },{
                    title: '成员',
                    field: 'users_id',
                    align: 'center',
                },{
                    title: '帐户',
                    field: 'accounts_id',
                    align: 'center',
                    formatter:function (value,row) {
                        var a = row.turn_out;
                        var b = row.accounts_id;
                        return a+"->"+b;
                    }
                },{
                    title: '流入',
                    field: 'money',
                    align: 'center',
                    sortable: true,
                    formatter: function (value, row) {
                        if (row.is_types == 1) {
                            return value
                        }
                    }
                },{
                    title: '流出',
                    field: 'money',
                    align: 'center',
                    sortable: true,
                    formatter: function (value, row) {
                        if (row.is_types == 2) {
                            return value
                        }
                    }
                },{
                    title: '备注',
                    field: 'desc',
                    align: 'center',
                },{
                    title: '时间',
                    field: 'created_at',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                }
            ],
        });
        function exportszls(x) {
            $('#table2').bootstrapTable('refreshOptions', {
                url: '/Admin/Accounts/stream',
                queryParams: function () {
                    return {
                        type:x
                    }
                }
            });
            $('#myModalzc').modal('show');
        }

        function yhzzzzls(x) {
            $('#table3').bootstrapTable('refreshOptions', {
                url: '/Admin/Transfers/Transfers_sel',
                queryParams: function () {
                    return {
                        id: x
                    }
                }
            });
            $('#myModalzz').modal('show');
        }
    </script>
@endsection()

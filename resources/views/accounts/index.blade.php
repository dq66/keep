@extends("layouts.app")
@section("css")
    @include("public.css")
@endsection()
@section("content")
    <table data-toggle="table" id="table">
        <div id="toolbar" style="display: flex">
            <button class="layui-btn layui-btn-small layui-btn-normal addBtn" data-url="add" data-id="2">
                <i class="layui-icon">&#xe654;</i></button>
            <button class="layui-btn layui-btn-small layui-btn-danger delBtn" data-url="article-add.html">
                <i class="layui-icon">&#xe640;</i></button>
            <select id="sel_exportoption" lay-filter="selecrex" class="form-control selecrex">
                <option value="basic">导出当前页面数据</option>
                <option value="all">导出全部数据</option>
                <option value="selected">导出选中数据</option>
            </select>
        </div>
    </table>
    <div style="display: none" class="add">
        <form method="post" action="/Admin/Accounts/create" class="layui-form column-content-detail">
            <div class="layui-tab-item layui-show">
                <div class="layui-form-item">
                    <label class="layui-form-label">账户类型：</label>
                    <div class="layui-input-block">
                        <select name="type" class="layui-input">
                            <option value="1" checked="">现金</option>
                            <option value="2">理财</option>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">账户名称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" required lay-verify="required" placeholder="请输入账户名称"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">当前余额：</label>
                    <div class="layui-input-block">
                        <input type="text" name="balance" required lay-verify="required" placeholder="请输入当前余额"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">备注：</label>
                    <div class="layui-input-block">
                        <textarea name="desc" class="layui-textarea"></textarea>
                    </div>
                </div>
            </div>
            {{ csrf_field() }}
            <div class="layui-form-item" style="padding-left: 10px;">
                <div class="layui-input-block">
                    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formDemo">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
    <div style="display: none" class="edit">
        <form method="post" action="/Admin/Accounts/edit" class="layui-form column-content-detail">
            <div class="layui-tab-item layui-show">
                <div class="layui-form-item">
                    <label class="layui-form-label">账户类型：</label>
                    <div class="layui-input-block">
                        <select name="type" id="types" class="layui-input">
                            <option value="1" id="type11" selected>现金</option>
                            <option value="2" id="type22">理财</option>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">账户名称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" id="name" required lay-verify="required" placeholder="请输入账户名称"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">当前余额：</label>
                    <div class="layui-input-block">
                        <input type="text" name="balance" id="balance" required lay-verify="required" placeholder="请输入当前余额"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">备注：</label>
                    <div class="layui-input-block">
                        <textarea name="desc" id="desc" class="layui-textarea"></textarea>
                    </div>
                </div>
            </div>
            {{ csrf_field() }}
            <input type="hidden" name="id" id="acid">
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
            url: 'Accounts/prefix_sel',              //请求后台的URL（*）
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
            showExport: true,           //显示导出按钮
            exportDataType: "basic",     //basic', 'all', 'selected'.
            exportTypes: ['excel', 'PDF', 'PNG'],
            exportOptions: {
                ignoreColumn: [0,6],  //忽略某一列的索引
                fileName: '账户管理',  //文件名称设置
                worksheetName: 'sheet1',  //表格工作区名称
                excelstyles: ['background-color', 'color', 'font-size', 'font-weight'],
                onMsoNumberFormat: $.DoOnMsoNumberFormat
            },
            sidePagination: "server", //服务端处理分页
            columns: [{
                checkbox: true,
                visible: true                  //是否显示复选框
            },{
                title:"id",
                field: 'id',
                align: 'center',
                valign: 'middle',
                sortable: true,//排序
                visible:false
            },{
                title: '账户类型',
                field: 'type',
                align: 'center',
                valign: 'middle',
                formatter: function (value,row) {
                    var v = row.type;
                    if (v == 1) {
                        return "<span>现金</span>"
                    } else {
                        return "<span>理财</span>"
                    }
                }
            },{
                title: '账户名称',
                field: 'name',
                align: 'center',
                valign: 'middle',
            },{
                title: '期初(创建时)金额',
                field: 'money',
                align: 'center',
                valign: 'middle',
            },{
                title: '当前余额',
                field: 'balance',
                align: 'center',
                valign: 'middle',
                sortable: true,//排序
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
                    var name = row.name;
                    return `<button class="layui-btn layui-btn-mini layui-btn-normal  edit-ac" data-id="${id}" data-url="edit"><i class="layui-icon"></i></button><button class="layui-btn layui-btn-mini layui-btn-danger del-btn" data-id="${id}" data-name="${name}" data-url="Accounts"><i class="layui-icon"></i></button>`
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
        });
    </script>
@endsection()

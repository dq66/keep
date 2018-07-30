@extends("layouts.app")
@section("css")
    @include("public.css")
@endsection()
@section("content")
    <table data-toggle="table" id="table">
        <div id="toolbar" style="display: flex">
            <button class="layui-btn layui-btn-small layui-btn-normal addBtn" data-url="add" data-id="6">
                <i class="layui-icon">&#xe654;</i></button>
            <select id="sel_exportoption" lay-filter="selecrex" class="form-control selecrex">
                <option value="basic">导出当前页面数据</option>
                <option value="all">导出全部数据</option>
                <option value="selected">导出选中数据</option>
            </select>
        </div>
    </table>
    <div style="display: none" class="add">
        <form method="post" action="/Admin/Users/create" class="layui-form column-content-detail">
            <div class="layui-tab-item layui-show">
                <div class="layui-form-item">
                    <label class="layui-form-label">登录名：</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" required lay-verify="required" placeholder="请输入用户名"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">真实名称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="realname" required lay-verify="required" placeholder="请输入用户名"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">邮箱：</label>
                    <div class="layui-input-block">
                        <input type="email" name="email" required lay-verify="required" placeholder="请输入邮箱"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">密码：</label>
                    <div class="layui-input-block">
                        <input type="password" name="password" required lay-verify="required" placeholder="请输入密码"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">确认密码：</label>
                    <div class="layui-input-block">
                        <input type="password" name="pwd" required lay-verify="required" placeholder="请输入确认密码"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">用户级别：</label>
                    <div class="layui-input-block jb">
                        <input type="radio" name="roles[]" value="0" title="cs">
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
        <form method="post" action="/Admin/Users/edit" class="layui-form column-content-detail">
            <div class="layui-tab-item layui-show">
                <div class="layui-form-item">
                    <label class="layui-form-label">登录名：</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" required lay-verify="required" placeholder="请输入用户名"
                               autocomplete="off" class="layui-input name">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">真实名称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="realname" required lay-verify="required" placeholder="请输入用户名"
                               autocomplete="off" class="layui-input realname">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">邮箱：</label>
                    <div class="layui-input-block">
                        <input type="email" name="email" required lay-verify="required" placeholder="请输入邮箱"
                               autocomplete="off" class="layui-input email">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">用户级别：</label>
                    <div class="layui-input-block ckjb">
                        <input type="radio" name="roles[]" value="0" title="cs">
                    </div>
                </div>
            </div>
            {{ csrf_field() }}
            <input type="hidden" name="id" id="id">
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
            url: '/Admin/Users/users_sel',              //请求后台的URL（*）
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
                ignoreColumn: [0,5],  //忽略某一列的索引
                fileName: '用户管理',  //文件名称设置
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
                title: '用户名',
                field: 'name',
                align: 'center',
                valign: 'middle',
            },{
                title: '真实姓名',
                field: 'realname',
                align: 'center',
                valign: 'middle',
            },{
                title: '邮箱',
                field: 'email',
                align: 'center',
                valign: 'middle',
            },{
                title: '等级',
                field: 'role',
                align: 'center',
                valign: 'middle',
            },{
                title: '最进登录IP',
                field: 'lastlogin_ip',
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
                    return `<button class="layui-btn layui-btn-mini layui-btn-normal" onclick="edit_user('${id}','edit')" ><i class="layui-icon"></i></button><button class="layui-btn layui-btn-mini layui-btn-danger" onclick="delone('${id}','${name}','Staffs')"><i class="layui-icon"></i></button>`
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
            //添加显示级别
            $('.addBtn').click(function () {
                $.ajax({
                    type:'get',
                    url:'/Admin/Users/rol_sel',
                    dataType:'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    success:function (da) {
                        //console.log(da);
                        var html = '';
                        for ($i=0;$i<da['data'].length;$i++){
                            html += `<input type="radio" name="roles[]"  lay-skin="primary" value="${da['data'][$i].id}" title="${da['data'][$i].name}">`;
                        }
                        $(".jb").html(html);
                        renderForm();//表单重新渲染
                    }
                });
            });
        });
    </script>
@endsection()

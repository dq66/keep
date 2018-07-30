@extends("layouts.app")
@section("css")
    @include("public.css")
@endsection()
@section("content")
    <table data-toggle="table" id="table">
        <div id="toolbar" style="display: flex">
            <button class="layui-btn layui-btn-small layui-btn-normal addBtn" data-url="add" data-id="7">
                <i class="layui-icon">&#xe654;</i></button>
            <button class="layui-btn layui-btn-small layui-btn-danger delBtn" data-url="Permissions">
                <i class="layui-icon">&#xe640;</i></button>
        </div>
    </table>
    <div style="display: none" class="add">
        <form method="post" action="/Admin/Permissions/create" class="layui-form column-content-detail">
            <div class="layui-tab-item layui-show">
                <div class="layui-form-item">
                    <label class="layui-form-label">角色名称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" required lay-verify="required" placeholder="请输入角色名称"
                               autocomplete="off" class="layui-input">
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
        <form method="post" action="/Admin/Permissions/edit" class="layui-form column-content-detail">
            <div class="layui-tab-item layui-show">
                <div class="layui-form-item">
                    <label class="layui-form-label">角色名称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" required lay-verify="required" placeholder="请输入角色名称"
                               autocomplete="off" class="layui-input name">
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
        $('#table').bootstrapTable({
            url: '/Admin/Permissions/permissions_sel',         //请求后台的URL（*）
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
            pageSize: 10, //每页的记录行数（*）
            sortable: true,             //是否启用排序
            sortOrder: "desc",           //排序方式
            sidePagination: "client", //服务端处理分页
            columns: [
                {
                    checkbox: true,
                    visible: true                  //是否显示复选框
                },{
                    title: 'ID',
                    field: 'id',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,//排序
                    visible: false
                },{
                    title: '角色',
                    field: 'name',
                    align: 'center',
                },{
                    title: "操作",
                    align: 'center',
                    valign: 'middle',
                    formatter: function (value,row) {
                        var id = row.id;
                        var name = row.name;
                        return `<button class="layui-btn layui-btn-mini layui-btn-normal" onclick="edit_perm('${id}','edit')"><i class="layui-icon"></i></button><button class="layui-btn layui-btn-mini layui-btn-danger" onclick="delone('${id}','${name}','Permissions')"><i class="layui-icon"></i></button>`
                    }
                }
            ],
        });
    </script>
@endsection()
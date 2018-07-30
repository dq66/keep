@extends("layouts.app")
@section("css")
    @include("public.css")
@endsection()
@section("content")
    <table data-toggle="table" id="table">
        <div id="toolbar" style="display: flex">
            <button class="layui-btn layui-btn-small layui-btn-normal addBtn" data-url="add" data-id="8">
                <i class="layui-icon">&#xe654;</i></button>
            <button class="layui-btn layui-btn-small layui-btn-danger delBtn" data-url="Role">
                <i class="layui-icon">&#xe640;</i></button>
        </div>
    </table>
    <div style="display: none" class="add">
        <form method="post" action="/Admin/Role/create" class="layui-form column-content-detail">
            <div class="layui-tab-item layui-show">
                <div class="layui-form-item">
                    <label class="layui-form-label">等级名称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" required lay-verify="required" placeholder="请输入等级名称"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">角色名称：</label>
                    <div class="layui-input-block rad">
                        <input type="radio" name="permissions[]" title="测试" checked="">
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
        <form method="post" action="/Admin/Role/edit" class="layui-form column-content-detail">
            <div class="layui-tab-item layui-show">
                <div class="layui-form-item">
                    <label class="layui-form-label">等级名称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" required lay-verify="required" placeholder="请输入等级名称"
                               autocomplete="off" class="layui-input name">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">角色名称：</label>
                    <div class="layui-input-block ra">
                        <input type="radio" name="permissions[]" title="测试" checked="">
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
            url: '/Admin/Role/role_sel',         //请求后台的URL（*）
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
                    title: '等级',
                    field: 'name',
                    align: 'center',
                },{
                    title: '角色',
                    field: 'perm',
                    align: 'center',
                    width: 860, // 定义列的宽度，单位为像素px
                    formatter:function (value,row) {
                        var perm = row.perm;
                        var per = '';
                        for ($i=0;$i<perm.length;$i++){
                            per += perm[$i]+',';
                        }
                        var data = per.substr(0,per.length-1);//去掉最后一个，号
                        return data;
                    }
                },{
                    title: "操作",
                    align: 'center',
                    valign: 'middle',
                    formatter: function (value,row) {
                        var id = row.id;
                        var name = row.name;
                        return `<button class="layui-btn layui-btn-mini layui-btn-normal" onclick="edit_roles('${id}','edit')"><i class="layui-icon"></i></button><button class="layui-btn layui-btn-mini layui-btn-danger" onclick="delone('${id}','${name}','Role')"><i class="layui-icon"></i></button>`
                    }
                }
            ],
        });
        $('.addBtn').click(function () {
            $.ajax({
                type:'get',
                url:'/Admin/Role/per',
                dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success:function (da) {
                    //console.log(da);
                    var html = '';
                    for ($i=0;$i<da['data'].length;$i++){
                        html += `<input type="checkbox" name="permissions[]"  lay-skin="primary" value="${da['data'][$i].id}" title="${da['data'][$i].name}">`;
                    }
                     $(".rad").html(html);
                    renderForm();//表单重新渲染
                }
            });
        });
    </script>
@endsection()
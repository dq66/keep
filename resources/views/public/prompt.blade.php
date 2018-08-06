<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="/admin/lib/bootstrap-table.js"></script>
<script src="https://cdn.bootcss.com/bootstrap-table/1.11.1/extensions/export/bootstrap-table-export.min.js"></script>
<!-- 引入中文语言包 -->
<script src="https://cdn.bootcss.com/bootstrap-table/1.11.1/locale/bootstrap-table-zh-CN.min.js"></script>
<script src="/admin/js/libs/pdfmake/pdfmake.min.js"></script>
<script src="/admin/js/libs/pdfmake/vfs_fonts.js"></script>
<script src="/admin/js/libs/FileSaver/FileSaver.min.js"></script>
<script src="/admin/js/libs/jsPDF/jspdf.min.js"></script>
<script src="/admin/js/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>
<script src="/admin/js/libs/tableExport.js"></script>
<script>
    //导入
    $('#dr').change(function () {
        $('.dral').click();
    });
    //权限提示
    function qx() {
        layer.msg("对不起！你没有改权限", {icon: "5"}, function () {});
    }
    function delone(id,name,url){
        layui.dialog.confirm({
            message:'您确定要 "'+name+'" 删除吗？',
            success:function(){
                $.ajax({
                    type:'get',
                    url: '/Admin/'+url+'/del/'+id,
                    dataType:'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    success:function (da) {
                        //console.log(da);
                        if(da['success'] == true){
                            layer.msg(da['msg'], {icon: "6"}, function () {});
                        }else if(da['data'] == 'err' ){
                            layer.msg("请先删除子分类", {icon: "5"}, function () {});
                        }else{
                            layer.msg(da['msg'], {icon: "5"}, function () {});
                        }
                        setTimeout(function () {
                            history.go(0);
                        }, 1500);
                    }
                });

            },
            cancel:function(){
                layer.msg('取消了')
            }
        });
        return false;
    }
    layui.use('layer', function () {
        var layer = layui.layer;
        //提示
        @if(Session::has('message'))
            layer.msg("{{Session::get('message')}}", {icon: "{{Session::get('icon')}}"}, function () {
            });
        @endif
        //字段验证
        @if($errors->any())
        @foreach($errors->all() as $error)
            layer.msg('{{ $error }}', {icon: 5}, {anim: 1});
        @endforeach
        @endif
    });
</script>
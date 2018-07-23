<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
{{--<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.js"></script>--}}
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
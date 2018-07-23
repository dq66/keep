<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>@yield("title")</title>
    <link rel="stylesheet" type="text/css" href="/admin/layui/css/layui.css"/>
    @yield('css')
</head>
<body>
@yield('content')
<script src="/admin/layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script src="/admin/js/common.js" type="text/javascript" charset="utf-8"></script>
<script src="/admin/js/main.js" type="text/javascript" charset="utf-8"></script>
@yield('js')
</body>
</html>

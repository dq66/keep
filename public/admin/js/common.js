layui.config({
	base: '/admin/js/module/'
}).extend({
	dialog: 'dialog',
});

layui.use(['form', 'jquery', 'laydate', 'layer', 'laypage', 'dialog',   'element'], function() {
	var form = layui.form(),
		layer = layui.layer,
		$ = layui.jquery,
		dialog = layui.dialog;
	//获取当前iframe的name值
	var iframeObj = $(window.frameElement).attr('name');
	//全选
	form.on('checkbox(allChoose)', function(data) {
		var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]');
		child.each(function(index, item) {
			item.checked = data.elem.checked;
		});
		form.render('checkbox');
	});
	//小类（类别变动）
    form.on('select(sz)', function(data){
        //console.log(data.value); //得到被选中的值
        $('#types_id option').remove();
        $.ajax({
            type:'get',
            url: '/Admin/Types/ajaxtyps/'+data.value,
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

                $('#types_id').append(html);
                renderForm();//表单重新渲染，要不然添加完显示不出来新的option
            }
        });
    });

    form.render('select'); //刷新select选择框渲染
    //渲染表单
	form.render();	
	//添加类别
	$('.addBtn').click(function() {
		var url=$(this).attr('data-url');
		var id =$(this).attr('data-id');
		//console.log(id);
		if(id == 1){
            //将iframeObj传递给父级窗口,执行操作完成刷新
            layer.open({
                type: 1,
                area: ['750px', '425px'],
                shade: 0.5,//遮罩
                shadeClose:true,//是否点击遮罩关闭
                resize:false,//是否允许拉伸
                scrollbar: false,//是否允许浏览器出现滚动条
                title: "添加账户",
                content: $("."+url), //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
            });

            return false;
		}else if(id == 2){//类别
            //将iframeObj传递给父级窗口,执行操作完成刷新
            layer.open({
                type: 1,
                area: ['750px', '425px'],
                shade: 0.5,
                shadeClose:true,
                title: "添加大类",
                content: $("."+url), //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
            });

            return false;
		}else if(id == 3){//客户
            //将iframeObj传递给父级窗口,执行操作完成刷新
            layer.open({
                type: 1,
                area: ['750px', '540px'],
                shade: 0.5,
                shadeClose:true,
                title: "添加客户",
                content: $("."+url), //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
            });

            return false;
		}else if(id == 4){
            //将iframeObj传递给父级窗口,执行操作完成刷新
            layer.open({
                type: 1,
                area: ['750px', '490px'],
                shade: 0.5,
                shadeClose:true,
                title: "添加员工",
                content: $("."+url), //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
            });

            return false;
		}else if(id == 5){
            //将iframeObj传递给父级窗口,执行操作完成刷新
            layer.open({
                type: 1,
                area: ['750px', '310px'],
                shade: 0.5,
                shadeClose:true,
                title: "添加项目",
                content: $("."+url), //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
            });

            return false;
		}else{
            //将iframeObj传递给父级窗口,执行操作完成刷新
            layer.open({
                type: 1,
                area: ['750px', '410px'],
                shade: 0.5,
                shadeClose:true,
                title: "添加小类",
                content: $("."+url), //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
            });
        }


	}).mouseenter(function() {

		dialog.tips('添加', '.addBtn');
	});
	//转账
    $('.tra_add').click(function () {
        var id = $(this).attr('data-id');
        var url = $(this).attr('data-url');
        var name = $(this).attr('data-name');
        $('.zc').val(name);
        $('#turn_out').val(id);
        $.ajax({
            type:'get',
            url:'/Admin/Accounts/trans/'+id,
            dataType:'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success:function (da) {
                var html = '';
                for ($i=0;$i<da['data'].length;$i++){
                    html += `<option value="${da['data'][$i].id}">${da['data'][$i].name}</option>`;
                }
                $("[name='accounts_id']").html(html);
                renderForm();//表单重新渲染，要不然添加完显示不出来新的option
            }
        });
        layer.open({
            type: 1,
            area: ['550px', '425px'],
            shade: 0.5,//遮罩
            shadeClose:true,//是否点击遮罩关闭
            resize:false,//是否允许拉伸
            scrollbar: false,//是否允许浏览器出现滚动条
            title: "转账",
            content: $("."+url), //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
        });
    });
	//编辑类别
	$('.edit-btn').click(function () {
		var url = $(this).attr('data-url');
		var id = $(this).attr('data-id');
		$.ajax({
            type:'get',
            url:'Types/edit/'+id,
            dataType:'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
			success:function (da) {
				//console.log(da);
				$("#name").val(da['data'].name);
				$("#desc").val(da['data'].desc);
				$("#typeid").val(da['data'].id);
				if(da['data'].is_types == 2){
				   // console.log(da['data'].is_types);
                    $(".rad1").removeAttr("checked");
                    $(".rad2").attr('checked',true);
				}
                layer.open({
                    type: 1,
                    area: ['750px', '370px'],
                    shade: 0.5,//遮罩
                    shadeClose:true,//是否点击遮罩关闭
                    resize:false,//是否允许拉伸
                    scrollbar: false,//是否允许浏览器出现滚动条
                    title: "编辑类别",
                    content: $("."+url), //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
                });
            }
		});

	});
    //编辑小类
    $('.edit-xl').click(function () {
        var url = $(this).attr('data-url');
        var id = $(this).attr('data-id');
        $.ajax({
            type:'get',
            url:'Xtypes/edit/'+id,
            dataType:'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success:function (da) {
                //console.log(da);
                $("#name").val(da['data'].name);
                $("#desc").val(da['data'].desc);
                $("#xid").val(da['data'].id);
                layer.open({
                    type: 1,
                    area: ['650px', '310px'],
                    shade: 0.5,//遮罩
                    shadeClose:true,//是否点击遮罩关闭
                    resize:false,//是否允许拉伸
                    scrollbar: false,//是否允许浏览器出现滚动条
                    title: "编辑小类",
                    content: $("."+url), //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
                });
            }
        });
    });
	//编辑客户
    $('.edit_cust').click(function () {
        var url = $(this).attr('data-url');
        var id = $(this).attr('data-id');
        $.ajax({
            type:'get',
            url:'/Admin/Customer/edit/'+id,
            dataType:'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success:function (da) {
                //console.log(da);
                $("#name").val(da['data'].name);
                $("#tel").val(da['data'].telephone);
                $("#fax").val(da['data'].fax);
                $("#email").val(da['data'].email);
                $("#address").val(da['data'].address);
                $("#desc").val(da['data'].desc);
                $("#custid").val(da['data'].id);
                layer.open({
                    type: 1,
                    area: ['750px', '540px'],
                    shade: 0.5,//遮罩
                    shadeClose:true,//是否点击遮罩关闭
                    resize:false,//是否允许拉伸
                    scrollbar: false,//是否允许浏览器出现滚动条
                    title: "编辑客户",
                    content: $("."+url), //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
                });
            }
        });

    });
    //编辑员工
	$('.edit_st').click(function () {
		var url =$(this).attr('data-url');
		var id = $(this).attr('data-id');
        $.ajax({
            type:'get',
            url:'/Admin/Staffs/edit/'+id,
            dataType:'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success:function (da) {
                //console.log(da);
                $("#name").val(da['data'].name);
                $("#department").val(da['data'].department);
                $("#telephone").val(da['data'].telephone);
                $("#birth").val(da['data'].birth);
                $("#desc").val(da['data'].desc);
                $("#stid").val(da['data'].id);
                layer.open({
                    type: 1,
                    area: ['750px', '490px'],
                    shade: 0.5,//遮罩
                    shadeClose:true,//是否点击遮罩关闭
                    resize:false,//是否允许拉伸
                    scrollbar: false,//是否允许浏览器出现滚动条
                    title: "编辑员工",
                    content: $("."+url), //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
                });
            }
        });
    });
	//编辑账户
	$('.edit-ac').click(function () {
        var url = $(this).attr('data-url');
        var id = $(this).attr('data-id');
        $.ajax({
            type:'get',
            url:'Accounts/edit/'+id,
            dataType:'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success:function (da) {
                //console.log(da);
                $("#name").val(da['data'].name);
                $("#balance").val(da['data'].balance);
                $("#desc").val(da['data'].desc);
                $("#acid").val(da['data'].id);
                if(da['data'].type == 2){
                    $("#types").find("option[text='现金']").removeAttr("selected");
                    $("#types").find("option[text='理财']").attr("selected",true);//设置text为理财的项选中

                }
                layer.open({
                    type: 1,
                    area: ['750px', '425px'],
                    shade: 0.5,//遮罩
                    shadeClose:true,//是否点击遮罩关闭
                    resize:false,//是否允许拉伸
                    scrollbar: false,//是否允许浏览器出现滚动条
                    title: "编辑账户",
                    content: $("."+url), //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
                });
            }
        });
    });
	//修改项目
	$('.edit-pr').click(function () {
        var url = $(this).attr('data-url');
        var id = $(this).attr('data-id');
        $.ajax({
            type:'get',
            url:'/Admin/Projects/edit/'+id,
            dataType:'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success:function (da) {
                //console.log(da);
                $("#name").val(da['data'].name);
                $("#desc").val(da['data'].desc);
                $("#prid").val(da['data'].id);
                if(da['data'].is_types == 2){
                    $("#rad11").removeAttr("checked");
                    $("#rad22").attr('checked',true);
                }
                layer.open({
                    type: 1,
                    area: ['750px', '310px'],
                    shade: 0.5,//遮罩
                    shadeClose:true,//是否点击遮罩关闭
                    resize:false,//是否允许拉伸
                    scrollbar: false,//是否允许浏览器出现滚动条
                    title: "编辑项目",
                    content: $("."+url), //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
                });
            }
        });
    });

    //转账流水详情
    $('.flow-btn').click(function () {
        var url = $(this).attr('data-url');
        layer.open({
            type:1,
            area:['750px','310px'],
            shade: 0.5,//遮罩
            shadeClose:true,//是否点击遮罩关闭
            resize:false,//是否允许拉伸
            scrollbar: false,//是否允许浏览器出现滚动条
            title:"转账流水详情",
            content:$("."+url)
        });
    });

    //列表删除
    $('.del-btn').click(function() {
        //console.log('dd');
        var url=$(this).attr('data-url');
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-name');
        dialog.confirm({
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
                            layer.msg('删除成功！')
						}else if(da['data'] == 'err' ){
                            layer.msg('请先删除子分类');
                        }else{
                            layer.msg('删除失败！')
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
    });

	//顶部排序
	$('.listOrderBtn').click(function() {
		var url=$(this).attr('data-url');
		dialog.confirm({
			message:'您确定要进行排序吗？',
			success:function(){
				layer.msg('确定了')
			},
			cancel:function(){
				layer.msg('取消了')
			}
		});
		return false;

	}).mouseenter(function() {

		dialog.tips('批量排序', '.listOrderBtn');

	});
	//顶部批量删除
	$('.delBtn').click(function() {
		var url=$(this).attr('data-url');
		dialog.confirm({
			message:'您确定要删除选中项',
			success:function(){
				layer.msg('删除了')
			},
			cancel:function(){
				layer.msg('取消了')
			}
		});
		return false;

	}).mouseenter(function() {

		dialog.tips('批量删除', '.delBtn');

	});
	//列表刷新
    $('.refresh').click(function () {
        window.location.reload();
    }).mouseenter(function () {
        dialog.tips('刷新','.refresh');
    });
	//列表添加
	$('#table-list').on('click', '.add-btn', function() {
		var url=$(this).attr('data-url');
		//将iframeObj传递给父级窗口
		parent.page("菜单添加", url, iframeObj, w = "700px", h = "620px");
		return false;
	});

	//列表跳转
	$('#table-list,.tool-btn').on('click', '.go-btn', function() {
		var url=$(this).attr('data-url');
		var id = $(this).attr('data-id');
		window.location.href=url+"?id="+id;
		return false;
	})
});

//重新渲染表单
function renderForm(){
    layui.use('form', function(){
        var form = layui.form();//高版本建议把括号去掉，有的低版本，需要加()
        form.render();
    });
}

/**
 * 控制iframe窗口的刷新操作
 */
var iframeObjName;

//父级弹出页面
function page(title, url, obj, w, h) {
	if(title == null || title == '') {
		title = false;
	};
	if(url == null || url == '') {
		url = "404.html";
	};
	if(w == null || w == '') {
		w = '700px';
	};
	if(h == null || h == '') {
		h = '350px';
	};
	iframeObjName = obj;
	//如果手机端，全屏显示
	if(window.innerWidth <= 768) {
		var index = layer.open({
			type: 2,
			title: title,
			area: [320, h],
			fixed: false, //不固定
			content: url
		});
		layer.full(index);
	} else {
		var index = layer.open({
			type: 2,
			title: title,
			area: [w, h],
			fixed: false, //不固定
			content: url
		});
	}
}

/**
 * 刷新子页,关闭弹窗
 */
function refresh() {
	//根据传递的name值，获取子iframe窗口，执行刷新
	if(window.frames[iframeObjName]) {
		window.frames[iframeObjName].location.reload();

	} else {
		window.location.reload();
	}

	layer.closeAll();
}
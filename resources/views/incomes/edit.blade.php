@extends("layouts.app")

@section("content")
<div class="page-content-wrap">
    <form action="/Admin/Incomes/edit" method="post">
        <div class="layui-tab" style="margin: 0;">
            <div class="layui-tab-content">
                <div class="layui-tab-item"></div>
                <div class="layui-tab-item layui-show">
                    <div class="layui-form-item">
                        <label class="layui-form-label">收支类型：</label>
                        <div class="layui-input-block">
                            <select name="is_types" class="layui-input sel">
                                @if($inc['is_types'] == 1)
                                    <option value="1" selected>收入</option>
                                    <option value="2">支出</option>
                                @else
                                    <option value="1">收入</option>
                                    <option value="2" selected>支出</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">所属大类：</label>
                        <div class="layui-input-block">
                            <input type="hidden" id="dl" value="{{$inc['types_id']}}">
                            <select name="types_id" id="types_id" class="layui-input types_id">

                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">所属小类：</label>
                        <div class="layui-input-block">
                            <input type="hidden" id="xl" value="{{$inc['parent_id']}}">
                            <select name="parent_id" id="parent_id" class="layui-input parent">

                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">金额：</label>
                        <div class="layui-input-block">
                            <input type="text" name="money" value="{{$inc['money']}}" required lay-verify="required"
                                   placeholder="请输入金额" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">金额账户：</label>
                        <div class="layui-input-block">
                            <select name="accounts_id" id="accounts_id" class="layui-input parent">
                                @foreach($acc as $ac)
                                    @if($inc['accounts_id'] == $ac->id)
                                        <option selected value="{{$ac->id}}">{{$ac->name}}</option>
                                    @else
                                        <option value="{{$ac->id}}">{{$ac->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">生意伙伴：</label>
                        <div class="layui-input-block">
                            <select name="customers_id" id="customers_id" class="layui-input parent">
                                @foreach($cust as $cu)
                                    @if($inc['customers_id'] == $cu->id)
                                        <option selected value="{{$cu->id}}">{{$cu->name}}</option>
                                    @else
                                        <option value="{{$cu->id}}">{{$cu->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">业务项目：</label>
                        <div class="layui-input-block">
                            <select name="projects_id" id="projects_id" class="layui-input parent">
                                @foreach($pro as $pr)
                                    @if($inc['projects_id'] == $pr->id)
                                        <option selected value="{{$pr->id}}">{{$pr->name}}</option>
                                    @else
                                        <option value="{{$pr->id}}">{{$pr->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">经办人：</label>
                        <div class="layui-input-block">
                            <select name="staffs_id" id="staffs_id" class="layui-input parent">
                                @foreach($sta as $st)
                                    @if($inc['staffs_id'] == $st->id)
                                        <option selected value="{{$st->id}}">{{$st->name}}</option>
                                    @else
                                        <option value="{{$st->id}}">{{$st->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">说明：</label>
                        <div class="layui-input-block">
                            <textarea name="desc" id="desc" class="layui-textarea">{{$inc['desc']}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{$inc['id']}}">
        <div class="layui-form-item" style="padding-left: 10px;">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formDemo">立即提交</button>
                <button type="button" class="layui-btn layui-btn-primary" onclick="javascript :history.back(-1);">返回</button>
            </div>
        </div>
    </form>
</div>
@endsection()

@section("js")
    @include("public.prompt")
<script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript">
    $(function () {
        type_all($('.sel option:selected').val());

        //类型
        $('.sel').change(function () {
            var lx = $('.sel option:selected').val();
            type_all(lx);
        });
        //大类
        $('.types_id').change(function () {
            var parent = $('.types_id option:selected').val();
            //console.log(parent);
            $.ajax({//查询小类
                type:'get',
                url: '/Admin/Types/types_lx/'+parent,
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
                    if(html == ''){
                        $('#parent_id').html(`<option>该分类下没有小分类</option>`);
                    }else{
                        $('#parent_id').html(html);
                    }

                }
            })
        });

        function type_all($lx) {
            $.ajax({//查出大类
                type:'get',
                url: '/Admin/Types/ajaxtyps/'+$lx,
                dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success:function (da) {
                    //console.log(da);
                    var html = '';
                    var dl = $('#dl').val();
                    for ($i=0;$i<da['data'].length;$i++){
                        if (da['data'][$i].id == dl){
                            html += `<option selected value="${da['data'][$i].id}">${da['data'][$i].name}</option>`;
                        }else{
                            html += `<option value="${da['data'][$i].id}">${da['data'][$i].name}</option>`;
                        }
                    }

                    $('#types_id').html(html);
                }
            });
            $.ajax({//查询小类
                type:'get',
                url: '/Admin/Types/xt/'+$lx,
                dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success:function (da) {
                    //console.log(da);
                    var html = '';
                    var xl = $('#xl').val();
                    for ($i=0;$i<da['data'].length;$i++){
                        if(da['data'][$i].id == xl){
                            html += `<option selected value="${da['data'][$i].id}">${da['data'][$i].name}</option>`;
                        }else {
                            html += `<option value="${da['data'][$i].id}">${da['data'][$i].name}</option>`;
                        }
                    }

                    $('#parent_id').html(html);
                }
            })
        }
    });
</script>
@endsection()
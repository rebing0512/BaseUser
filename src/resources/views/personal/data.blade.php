@extends('mbcore.baseuser::layout.iframe')
@section('title', $subtitle)

@section('content')

    <style>
        .radio label{width:60px;}
        /*.change-button{*/
            /*position: absolute;*/
            /*top: 1px;*/
            /*right: 30%;*/
        /*}*/
    </style>
    <div class="wrapper wrapper-content animated fadeInRight">

        <div class="row">
            <div class="col-sm-12">

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{{$subtitle}}</h5>
                    </div>
                    <div class="ibox-content">

                        <div  class="form-horizontal">

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">用户名</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">{{$data['username']}}</p>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">登录密码</label>
                                <div class="col-sm-10">
                                    <button type="button" class="btn btn-primary btn-xs password-button" style="margin-top: 5px;">修改密码</button>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">绑定手机号</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">{{$data['phone']}}</p>

                                    {{--<button type="button" class="btn btn-primary btn-xs change-button">修改手机号</button>--}}

                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">邮箱</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">{{$data['email']}}</p>
                                    {{--<button type="button" class="btn btn-primary btn-xs change-button">修改邮箱</button>--}}
                                </div>
                            </div>

                            {{--<div class="hr-line-dashed"></div>--}}
                            {{--<div class="form-group">--}}
                                {{--<label class="col-sm-2 control-label">全称</label>--}}
                                {{--<div class="col-sm-10">--}}
                                    {{--<p class="form-control-static">{{$data['fullName']}}</p>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">最后登录时间</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">{{$data['last_login_time']}}</p>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">最后登录IP</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">{{$data['last_login_ip']}}</p>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">注册方式</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">
                                        @if($data->register_method ==1)
                                            用户注册
                                        @else
                                            后台添加
                                        @endif</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('myscript')
    <script>
        $('body').on('click', 'button.password-button', function() { //查看
            // var url = url.replace('__id__',$(this).val()); //this代表删除按钮的DOM对象;
            var url = "{{route('user.personal.changePassword')}}";
            layer.open({
                type: 2,
                title: '修改密码',
                shadeClose: true,
                shade: 0.8,
                maxmin: true,
                area: ['90%', '90%'],
                content: url //iframe的url
            });
        })
    </script>

@stop
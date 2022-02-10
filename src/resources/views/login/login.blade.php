@extends('mbcore.baseuser::layout.base')
@section('title', config('mbcore_baseuser.baseuser_name')." - 登录")

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .btn-w-m{
            min-width: 100px !important;
        }
        .code-button{
            float: right;
            position: absolute;
            bottom: @if (count($errors) > 0) 50.7% @else 39% @endif;
            right: 13%;
        }
    </style>
    <div class="signinpanel">
        <div class="row">
            <div class="col-sm-12">
                {!! Form::open(['url' =>route('user.login.auth')]) !!}

                     {{--登录方式--}}
                    <div>
                        <h4 class="no-margins password-login" style="display: block;float: left;">
                            <a href="javascript:;" style="color: #6254b2;"> 密码登录</a>
                        </h4><!--23b7e5-->
                        <h4 class="no-margins phone-vcode" style="float: left; display: block;padding-left: 50%;">
                            <a href="javascript:;" style="color: #6254b2;">手机号登录</a>
                        </h4>
                    </div>

                    <p class="m-t-md" style="margin-top: 40px !important;">登录到{{config('mbcore_baseuser.baseuser_name')}}</p>

                    {{--密码登录--}}
                    <div class="password" style="@if($loginType == 'vcode')display: none;@endif " >
                        {!! Form::text('username',null,['class'=>'form-control uname','placeholder'=>'用户名','required'=>'','id'=>"username"]) !!}
                        <input type="password" class="form-control pword m-b" placeholder="密码" name="password" id="password" required=""/>
                    </div>

                    {{--验证码登录--}}
                    <div class="vcode" style="@if($loginType == 'password')display: none;@endif ">
                        {!! Form::text('phone',null,['class'=>'form-control  mobile-phone-input','placeholder'=>'手机号','id'=>'phone']) !!}
                        {!! Form::text('code',null,['class'=>'form-control m-b','placeholder'=>'验证码','style'=>'width: 59%;','id'=>'vcode']) !!}
                        {!! Form::button('获取验证码',['class'=>'btn btn-w-m btn-primary code-button','id'=>'getCode']) !!}
                    </div>

                    {{--错误提示--}}
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {!! Form::hidden('loginType','password',['id'=>'loginType']) !!}
                    {!! Form::submit('登录',['class'=>'btn btn-success btn-block']) !!}
                    <p class="text-muted text-center" style="margin-top: 10px;">
                        <a href="{{route('user.personal.forgotPassword')}}" class="forgot-password"><small>忘记密码了？</small></a> |
                        <a href="{{route('user.register.index')}}">注册一个新账号</a>
                    </p>
                    {{ csrf_field() }}
                {!! Form::close() !!}
            </div>
        </div>

        <div class="signup-footer">
            <div class="pull-left">
                &copy; MBCore.COM
            </div>
        </div>
    </div>

    <script src="{{config('mbcore_baseuser.baseuser_assets_path')}}/js/jquery.min.js?v=2.1.4"></script>

    <!-- Sweet Alert -->
    <script src="{{config('mbcore_baseuser.baseuser_assets_path')}}/js/plugins/sweetalert/sweetalert.min.js"></script>
    <link href="{{config('mbcore_baseuser.baseuser_assets_path')}}/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

    {{-- 发送验证码 --}}
    <script>
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        var type = 'LOGIN';
        var url = '{{route("user.register.getCode")}}';
    </script>
    @include('mbcore.baseuser::layout.get_code_js')

    <script>
        {{--密码登录--}}
        $(".password-login").click(function(){
            $(".password").show();
            $("#loginType").val('password');
            $("#username").attr("required",'');
            $("#password").attr("required",'');

            $(".vcode").hide();
            $("#phone").removeAttr("required");
            $("#vcode").removeAttr("required")
        });

        // 验证码登录
        $(".phone-vcode").click(function(){
            $(".password").hide();
            $("#username").removeAttr("required");
            $("#password").removeAttr("required");

            $(".vcode").show();
            $("#loginType").val('vcode');
            $("#phone").attr("required",'');
            $("#vcode").attr("required",'');
        });
    </script>
@stop
@extends('mbcore.baseuser::layout.iframe')
@section('title', '找回密码')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .radio label{width:60px;}
        .wrapper{
            width: 50%;
            margin-left: 25%;
        }
        .code-button{
            float: right;
            position: absolute;
            bottom:24% ;
            right: 3%;
        }
        .back{
            position: absolute;
            right: 5%;
            top: 1.5%;
        }
    </style>
    <div class="wrapper wrapper-content animated fadeInRight">


        <div class="row">
            <div class="col-sm-12">

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>手机号验证</h5>
                        <a href="{{route('user.personal.forgotPassword',['username'=>$username])}}" class="back"><button class="btn btn-primary">上一步</button></a>
                    </div>
                    <div class="ibox-content">
                        {!! Form::open(['enctype'=>'multipart/form-data','class'=>'form-horizontal']) !!}

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            {!! Form::label('phone','手机号',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('phone',null,['class'=>'form-control phone  mobile-phone-input','placeholder'=>'请输入手机号','required']) !!}
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            {!! Form::label('captcha','验证码',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('code',null,['class'=>'form-control m-b','placeholder'=>'验证码','style'=>'width: 75%;','id'=>'vcode']) !!}
                                {!! Form::button('获取验证码',['class'=>'btn btn-w-m btn-primary code-button','id'=>'getCode','required']) !!}
                            </div>
                        </div>

                        @if (count($errors) > 0)
                            @if(count($errors)==1 && $errors->all()[0] == 'success')
                            <!--添加成功提示-->
                            @else
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @endif
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                {!! Form::submit('下一步',['class'=>'btn btn-primary']) !!}
                            </div>
                        </div>
                        {!! Form::hidden('type','phone') !!}
                        {!! Form::hidden('username',$username) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('myscript')
    <script>

        var param = '?type=password' + '&phone=' + $("#phone").val();
        var urlNext = '{{route("user.personal.forgotPassword")}}'+param;
        $(document).ready(function () {

            @if(count($errors)==1 && $errors->all()[0] == 'success')
                swal({
                    title: "恭喜，验证通过！",
                    text: "请继续操作。",
                    type: "success",
                    timer: 2000,
                    showConfirmButton: false
                }, function () {
                    location.href = urlNext;
                });
            @endif
        });
    </script>

    {{-- 发送验证码 --}}
    <script>
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        var type = 'RESETPASSWORD';
        var url = '{{route("user.register.getCode")}}';
    </script>
    @include('mbcore.baseuser::layout.get_code_js')
@stop
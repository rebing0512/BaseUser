@extends('mbcore.baseuser::layout.iframe')
@section('title', '找回密码')

@section('content')

    <style>
        .radio label{width:60px;}
        .wrapper{
            width: 50%;
            margin-left: 25%;
        }
        .captcha-imput{
            margin-top: 6px;
        }
    </style>
    <div class="wrapper wrapper-content animated fadeInRight">


        <div class="row">
            <div class="col-sm-12">

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>请输入您要找回密码的用户名</h5>
                    </div>
                    <div class="ibox-content">
                        {!! Form::open(['enctype'=>'multipart/form-data','class'=>'form-horizontal']) !!}

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            {!! Form::label('username','用户名',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('username',$username,['class'=>'form-control','placeholder'=>'请输入用户名','required']) !!}
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            {!! Form::label('captcha','验证码',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                <img src="{{captcha_src()}}" style="cursor: pointer" onclick="this.src='{{captcha_src()}}'+Math.random()">
                                {!! Form::text('captcha',null,['class'=>'form-control captcha-imput','placeholder'=>'请输入验证码','required']) !!}
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
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('myscript')
    <script>
        var username = $('#username').val();
        var param = '?type=phone' + '&username=' + username;
        {{--var url = '{{route("user.personal.forgotPassword",["type"=>"phone","username"=>"__username__"])}}'.replace('__username__',username);;--}}
        var urlNext = '{{route("user.personal.forgotPassword")}}'+ param;

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

@stop
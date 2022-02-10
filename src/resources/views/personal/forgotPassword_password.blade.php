@extends('mbcore.baseuser::layout.iframe')
@section('title', '找回密码')

@section('content')

    <style>
        .radio label{width:60px;}
        .wrapper{
            width: 50%;
            margin-left: 25%;
        }
    </style>
    <div class="wrapper wrapper-content animated fadeInRight">


        <div class="row">
            <div class="col-sm-12">

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>重置密码</h5>
                    </div>
                    <div class="ibox-content">
                        {!! Form::open(['enctype'=>'multipart/form-data','class'=>'form-horizontal']) !!}

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            {!! Form::label('new_password','新密码',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::password('new_password',['class'=>'form-control','required']) !!}
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            {!! Form::label('confirm_password','确认密码',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::password('confirm_password',['class'=>'form-control','required']) !!}
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
                                {!! Form::submit('重置密码',['class'=>'btn btn-primary']) !!}
                            </div>
                        </div>
                        {!! Form::hidden('type','password') !!}
                        {!! Form::hidden('phone',$phone) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('myscript')
    <script>
        var urlOver = '{{route("user.login.login")}}';
        $(document).ready(function () {
            @if(count($errors)==1 && $errors->all()[0] == 'success')
            swal({
                title: "恭喜，密码重置成功！",
                text: "请登录。",
                type: "success",
                timer: 2000,
                showConfirmButton: false
            }, function () {
                location.href = urlOver;
            });
            @endif
        });
    </script>

@stop
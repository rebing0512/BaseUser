@extends('mbcore.baseuser::layout.iframe')
@section('title', $subtitle)

@section('content')
    <style>
        h4 strong{
           color: #b4c2b3;
        }
        .form-group{
            margin-left: 10px;
        }
    </style>
<div class="wrapper wrapper-content">
    <div class="row animated fadeInRight">
        <div class="col-sm-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>{{$subtitle}}</h5>
                </div>
                <div>

                    <div class="ibox-content profile-content">
                        <h4><strong>用户名</strong></h4>
                        <p><i class="fa fa-user"></i> {{$data->username}}</p>

                        <h4><strong>邮箱</strong></h4>
                        <p><i class="fa fa fa-mail-forward"></i> {{$data->email}}</p>

                        <h4><strong>手机号</strong></h4>
                        <p><i class="fa fa-mobile-phone"></i> {{$data->phone}}</p>

                        <h4><strong>全称</strong></h4>
                        <p><i class="fa fa-user-plus"></i> {{$data->fullName}}</p>

                        <h4><strong>最后登录时间</strong></h4>
                        <p><i class="fa fa-clock-o"></i> {{$data->last_login_time}}</p>

                        <h4><strong>最后登录IP</strong></h4>
                        <p><i class="fa fa-tripadvisor"></i> {{$data->last_login_ip}}</p>

                        <h4><strong>注册方式</strong></h4>
                        <p><i class="fa fa-recycle"></i>
                            @if($data->register_method ==1)
                                用户注册
                            @else
                                后台添加
                            @endif
                        </p>

                    </div>


                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>修改个人资料</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="profile.html#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="profile.html#">选项1</a>
                            </li>
                            <li><a href="profile.html#">选项2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">

                    <div>
                        <div class="feed-activity-list">

                            <div class="feed-element">
                                {!! Form::open() !!}
                                <label class="pull-left">用户名</label>
                                <div class="media-body ">
                                    <div class="form-group">
                                        {!! Form::text('username',$data->username,['class'=>'form-control','placeholder'=>'请输入用户名','required'=>'']) !!}
                                    </div>
                                </div>

                                <label class="pull-left">用户名</label>
                                <div class="media-body ">
                                    <div class="form-group">
                                        {!! Form::text('username',$data->username,['class'=>'form-control','placeholder'=>'请输入用户名','required'=>'']) !!}
                                    </div>
                                </div>


                                <label class="pull-left">用户名</label>
                                <div class="media-body ">
                                    <div class="form-group">
                                        {!! Form::text('username',$data->username,['class'=>'form-control','placeholder'=>'请输入用户名','required'=>'']) !!}
                                    </div>
                                </div>


                                <label class="pull-left">用户名</label>
                                <div class="media-body ">
                                    <div class="form-group">
                                        {!! Form::text('username',$data->username,['class'=>'form-control','placeholder'=>'请输入用户名','required'=>'']) !!}
                                    </div>
                                </div>


                                <label class="pull-left">用户名</label>
                                <div class="media-body ">
                                    <div class="form-group">
                                        {!! Form::text('username',$data->username,['class'=>'form-control','placeholder'=>'请输入用户名','required'=>'']) !!}
                                    </div>
                                </div>


                                <label class="pull-left">用户名</label>
                                <div class="media-body ">
                                    <div class="form-group">
                                        {!! Form::text('username',$data->username,['class'=>'form-control','placeholder'=>'请输入用户名','required'=>'']) !!}
                                    </div>
                                </div>



                                <label class="pull-left">用户名</label>
                                <div class="media-body ">
                                    <div class="form-group">
                                        {!! Form::text('username',$data->username,['class'=>'form-control','placeholder'=>'请输入用户名','required'=>'']) !!}
                                    </div>
                                </div>

                            </div>
                            {!! Form::close() !!}
                            <div class="feed-element">
                                <a href="profile.html#" class="pull-left">
                                    <img alt="image" class="img-circle" src="img/profile.jpg">
                                </a>
                                <div class="media-body ">
                                    <small class="pull-right">5分钟前</small>
                                    <strong>作家崔成浩</strong> 发布了一篇文章
                                    <br>
                                    <small class="text-muted">今天 10:20 来自 iPhone 6 Plus</small>

                                </div>
                            </div>

                            <div class="feed-element">
                                <a href="profile.html#" class="pull-left">
                                    <img alt="image" class="img-circle" src="img/a2.jpg">
                                </a>
                                <div class="media-body ">
                                    <small class="pull-right">2小时前</small>
                                    <strong>作家崔成浩</strong> 抽奖中了20万
                                    <br>
                                    <small class="text-muted">今天 09:27 来自 Koryolink iPhone</small>
                                    <div class="well">
                                        抽奖，人民币2000元，从转发这个微博的粉丝中抽取一人。11月16日平台开奖。随手一转，万一中了呢？
                                    </div>
                                    <div class="pull-right">
                                        <a class="btn btn-xs btn-white"><i class="fa fa-thumbs-up"></i> 赞 </a>
                                        <a class="btn btn-xs btn-white"><i class="fa fa-heart"></i> 收藏</a>
                                        <a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i> 评论</a>
                                    </div>
                                </div>
                            </div>
                            <div class="feed-element">
                                <a href="profile.html#" class="pull-left">
                                    <img alt="image" class="img-circle" src="img/a3.jpg">
                                </a>
                                <div class="media-body ">
                                    <small class="pull-right">2天前</small>
                                    <strong>天猫</strong> 上传了2张图片
                                    <br>
                                    <small class="text-muted">11月7日 11:56 来自 微博 weibo.com</small>
                                    <div class="photos">
                                        <a target="_blank" href="http://24.media.tumblr.com/20a9c501846f50c1271210639987000f/tumblr_n4vje69pJm1st5lhmo1_1280.jpg">
                                            <img alt="image" class="feed-photo" src="img/p1.jpg">
                                        </a>
                                        <a target="_blank" href="http://37.media.tumblr.com/9afe602b3e624aff6681b0b51f5a062b/tumblr_n4ef69szs71st5lhmo1_1280.jpg">
                                            <img alt="image" class="feed-photo" src="img/p3.jpg">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="feed-element">
                                <a href="profile.html#" class="pull-left">
                                    <img alt="image" class="img-circle" src="img/a4.jpg">
                                </a>
                                <div class="media-body ">
                                    <small class="pull-right text-navy">5小时前</small>
                                    <strong>在水一方Y</strong> 关注了 <strong>那二十年的单身</strong>.
                                    <br>
                                    <small class="text-muted">今天 10:39 来自 iPhone客户端</small>
                                    <div class="actions">
                                        <a class="btn btn-xs btn-white"><i class="fa fa-thumbs-up"></i> 赞 </a>
                                        <a class="btn btn-xs btn-white"><i class="fa fa-heart"></i> 收藏</a>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <button class="btn btn-primary btn-block m"><i class="fa fa-arrow-down"></i> 显示更多</button>

                    </div>

                </div>
            </div>

        </div>
    </div>
</div>


@stop

@section('myscript')


<!-- Peity -->
<script src="{{config('mbcore_baseuser.baseuser_assets_path')}}/js/plugins/peity/jquery.peity.min.js"></script>

<!-- Peity -->
<script src="{{config('mbcore_baseuser.baseuser_assets_path')}}/js/demo/peity-demo.js"></script>
@stop

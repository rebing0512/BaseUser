<?php
return [
    'baseuser_development' => false, //true false 开发模式显示菜单配置，设置false不显示开发模式
    'baseuser_assets_path' =>'/assets/Jenson/BaseUser',  //设置发布后样式路径
    'baseuser_name' => '通用管理后台系统',  //登录页面的显示系统名称名字
    'baseuser_basemodule_name' => '',  //登录页面的显示系统名称名字
    'baseuser_background_image' => '/img/login-background.jpg',  //登录页面的背景图片
    'baseuser_homeView' => '',  //显示的模板内容，默认空为系统内置首页模板
    'baseuser_homeRoute' => '',  //显示的路由内容，设置此项会覆盖baseuser_homeView的设置，为应用最高级别  baseadmin.test
    'baseuser_homeView_name' => '主页',  //显示的"主页"的名称，默认空为为"主页"
    'baseuser_menuGroup' => ['测试模块1','测试模块2'], //菜单的分组
    'baseuser_roles_home_subroles' => [
        ['name'=>'超级管理员','flag'=>'home_1'],
        ['name'=>'高级管理员','flag'=>'home_2'],
        ['name'=>'中级管理员','flag'=>'home_3'],
        ['name'=>'低级管理员','flag'=>'home_4'],
    ],
    'sms'=>[
        'templates' => [
            'id' => env('system_templates_id',null),
            'vars' =>  env('system_templates_vars',['code','time'])
        ],
        'key' => [
            'appid' => env('system_sms_appId',null),
            'appkey' => env('system_sms_appKey',null)
        ],
    ],
    'baseuser_system_name' => 'Jenson',
    'baseuser_loginView' => 'login',  //用户登录路由和登录页的物理路径【两者必须一致】，默认为login
    'baseuser_leftMenuCss' => '',  //自定义左侧菜单样式，默认''为系统内置样式
    'baseuser_ButtonCssGroup' => [

    ],  //自定义样式，默认[]为系统内置样式
    'baseuser_itemLogo' => '',  //自定义项目Logo，默认''为系统内置Logo
    'baseuser_message' => false,  //是否显示消息提醒，true 显示，false 不显示，默认false
    'baseuser_password_change_route' => '',  // 修改密码路由，默认''为系统默认路由，否则为自定义路由
];
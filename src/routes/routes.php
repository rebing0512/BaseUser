<?php
use Jenson\BaseUser\Middleware\BaseUserMiddleware;
use Jenson\BaseUser\Middleware\BaseUserMenuMiddleware;

Route::get('baseuser/test', function () {
    return 'baseuser.test welcome';
})->name("baseuser.test")->middleware('web');
Route::get('baseuser/rolestest', 'UserController@rolestest')->name("baseuser.rolestest")->middleware('web');



// Login 路由
Route::group([
    'prefix' =>'/user/login',
    'middleware'=>'web'
],function(){
    Route::get('login','LoginController@login')->name('user.login.login');
    // 验证登录（密码和或手机号）
    Route::post('auth','LoginController@auth')->name("user.login.auth");
    // 手机号登陆验证，没有实际的注册流程
    Route::post('loginValidate','LoginController@loginValidate')->name("user.login.validate");
});

// Register
Route::group([
    'prefix' =>'/user/register',
    'middleware'=>'web'
],function(){
    Route::get('index','RegisterController@index')->name('user.register.index');
    // 注册验证
    Route::post('auth','RegisterController@auth')->name("user.register.auth");
    // 验证码获取
    Route::post('getCode','RegisterController@getCode')->name("user.register.getCode");
});



// Personal
Route::group([
    'prefix' =>'/user/personal',
    'middleware' => ['web',BaseUserMiddleware::class]
],function(){
    // 个人资料 UserController
    Route::match(['post','get'],'data','UserController@data')->name('user.personal.data');
    // 修改密码
    Route::match(['post','get'],'changePassword','UserController@changePassword')->name("user.personal.changePassword");
    // 忘记密码
    Route::match(['post','get'],'forgotPassword','UserController@forgotPassword')->name("user.personal.forgotPassword");
});


// User 路由
Route::group([
    'prefix' =>'user',
    'middleware' => ['web',BaseUserMiddleware::class]
],function() {
    //Api 接口
    Route::post('edit', 'UserController@editsave')->name("user.editsave"); //管理员保存
    Route::post('getRole', 'UserController@getRole')->name("user.getRole"); //获得权限
    Route::post('saveRole', 'UserController@saveRole')->name("user.saveRole"); //保存权限



    // 退出登录
    Route::get('logout', 'LoginController@logout')->name("user.login.logout");  //退出登录

    // 控制台首页
    Route::get('index', 'UserController@index')->name("user.default");//控制台
    Route::get('home', 'UserController@home')->name("user.home");//控制台

    // 管理员账号管理
    Route::get('add', 'UserController@add')->name("user.add");//管理员添加
    Route::post('add', 'UserController@addsave')->name("user.addsave"); //管理员保存
    Route::get('list', 'UserController@list')->name("user.list");//管理员列表

    // 菜单相关
    Route::group([
        'prefix' => 'menu',
        'middleware' => [BaseUserMenuMiddleware::class]
    ], function () {
        Route::get('add', 'MenuController@add')->name("user.menu.add");//菜单添加
        Route::post('add', 'MenuController@addsave')->name("user.menu.addsave"); //菜单保存
        Route::get('list', 'MenuController@list')->name("user.menu.list");//菜单列表
        Route::post('edit', 'MenuController@editsave')->name("user.menu.editsave"); //菜单保存
    });

});
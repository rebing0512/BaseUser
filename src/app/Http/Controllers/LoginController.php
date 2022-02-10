<?php
namespace Jenson\BaseUser\Controllers;

use Illuminate\Http\Request;

use Jenson\BaseUser\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Jenson\MCore\Libraries\Helper as MCHelper;

Class LoginController extends BaseController
{
    /**
     * @param Request $request
     * @return mixed
     *
     * Login Page
     */
    public function login(Request $request)
    {
        if (session('user.is_login')) {
            return redirect()->route('user.default');
        }

        if(config('mbcore_baseuser.baseuser_loginView') == 'login'){
            $loginType = $request->get('loginType','password');
            return view('mbcore.baseuser::login.login',compact('loginType'));
        }else{
            $loginType = $request->get('loginType','vcode');
            //dd(config('mbcore_baseuser.baseuser_login_route'));
            return view(config('mbcore_baseuser.baseuser_loginView'),compact('loginType'));
        }

//        return view('mbcore.baseuser::login.login',compact('loginType'));
    }

    /**
     * @param Request $request
     * @return mixed
     *
     * Login auth
     */
    public function auth(Request $request)
    {
        if(config('mbcore_baseuser.baseuser_loginView') == 'login'){
            $loginType = $request->get('loginType','password');
        }else{
            $loginType = $request->get('loginType','vcode');
        }
        if($loginType =='password'){
            $validator = \Validator::make($request->all(),[
                'username' => 'required',
                'password' => 'required',
            ], [
                'username.required' => '用户名不能为空',
                'password.required' => '密码不能为空'
            ]);
        }else{
            $validator = \Validator::make($request->all(),[
                'phone' => 'required',
                'code' => 'required',
            ], [
                'phone.required' => '手机号不能为空',
                'code.required' => '验证码不能为空'
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if($loginType == 'vcode'){
            $redirect = route('user.login.login',['loginType'=>'vcode']);
            //手机号-验证码 登录
            $phone = $request->get('phone');
            if(!empty($phone)){
                $vp = MCHelper::telephoneNumber($phone,true);
                if($vp['code'] == 0){
                    return redirect($redirect)->withErrors($vp['msg'])->withInput();
                }
            }
            $user = User::query()->where('phone',$phone)->first();
            if (!$user)
                return redirect($redirect)->withErrors("手机号未注册")->withInput();
//                return redirect()->back()->withErrors("手机号未注册")->withInput();
            if ($user->status != 1)
                return redirect($redirect)->withErrors(["该用户已被锁定"])->withInput();

            $code = $request->get('code');
            $appName = strtoupper(config('mbcore_mcore.app_name'));
            $cacheKey = md5('SMS'.$phone.$appName.'-LOGIN');
            $captcha = \Cache::get($cacheKey);
            if(empty($captcha)){
                return redirect($redirect)->withErrors('验证码已失效')->withInput();
            }
            if($code != $captcha){
                return redirect($redirect)->withErrors('验证码错误')->withInput();
            }

            \Cache::forget($cacheKey);
        }else{
            // :todo 记录登录状态，暂不考虑
            /*
            $request->remember == 'on' ? $remember = true : $remember = false;
            if($remember == true){     //如果用户选择了，记录登录状态就把用户名和加了密的密码放到cookie里面
                setcookie("username", $name, time()+3600*24*30);
                setcookie("password", $request->get('password'), time()+3600*24*30);
            }
            //*/
            $redirect = route('user.login.login',['loginType'=>'password']);
            //用户名-密码 登录
            $name = $request->get('username');
            $user = User::query()->where('username',$name)->first();
            if (!$user)
                return redirect($redirect)->withErrors(["用户名不存在"])->withInput();

            if ($user->status != 1)
                return redirect($redirect)->withErrors(["该用户已被锁定"])->withInput();


            $password = $request->get('password');
            if (!password_verify($password,$user->password))
                return redirect($redirect)->withErrors(["用户名或密码错误"])->withInput();
        }


        //获取用户登录时间,IP储存
        $loginTime = Carbon::now();
        $loginIP = $_SERVER['REMOTE_ADDR'];

        //将用户登录时间,IP储存
        User::query()->where('id',$user->id)->update([
            'last_login_time' => $loginTime,
            'last_login_ip' => $loginIP,
        ]);
        //将用户信息闪存入session
        session(['user.uid' => $user->id]);
        session(['user.username' => $user->username]);
        session(['user.phone' => $user->phone]);
        session(['user.is_login' => true]);

        //设置权限信息
        $this->setUserRolesSession($request,$user->roles);

        return redirect()->route('user.default');
//        if ($request->get('username')){
//            //用户名登录
//            $name = $request->get('username');
//            $user = User::query()->where('username',$name)->first();
//        }
//
//        if (!$user){
//            return redirect()->back()->withErrors(["用户名不存在"])->withInput();
//        }else{
//            $password = $request->get('password');
//            if (password_verify($password,$user->password)){
//                //获取用户登录时间,IP储存
//                $loginTime = Carbon::now();
//                $loginIP = $_SERVER['REMOTE_ADDR'];
//
//                //将用户登录时间,IP储存
//                User::query()->where('id',$user->id)->update([
//                    'last_login_time' => $loginTime,
//                    'last_login_ip' => $loginIP,
//                ]);
//                //将用户信息闪存入session
//                session(['user.uid' => $user->id]);
//                session(['user.username' => $user->username]);
//                session(['user.is_login' => true]);
//
//                //设置权限信息
//                $this->setUserRolesSession($request,$user->roles);
//
//                return redirect()->route('user.default');
//            }else{
//                return redirect()->back()->withErrors(["用户名或密码错误"])->withInput();
//            }
//        }
    }

    /**
     * @param Request $request
     * @return mixed
     *
     * Login out
     */
    public function logout(Request $request)
    {
        if ($request->session()->has('user.is_login')) {
            $is_forgotten = session()->flush(); //删除所有session数据
            if ($is_forgotten === null)
                if(config('mbcore_baseuser.baseuser_loginView')){
                    return redirect()->route(config('mbcore_baseuser.baseuser_loginView'));
                }
            return redirect()->route('user.login.login');
        }
        return redirect()->back()->withErrors('系统异常，退出失败,请重试');
    }

    /**
     * @param Request $request
     * @return mixed
     *
     * 纯手机号登陆，没有实际的注册流程
     */
    public function loginValidate(Request $request){
        $validator = \Validator::make($request->all(),[
            'phone' => 'required',
            'code' => 'required',
        ], [
            'phone.required' => '手机号不能为空',
            'code.required' => '验证码不能为空'
        ]);

        if ($validator->fails()) {
            return [
                'code' => 0,
                'result' => [
                    'msg' => implode("<br/>", $validator->errors()->all())
                ]
            ];
//            return redirect()->back()->withErrors($validator)->withInput();
        }

        $phone = $request->get('phone');
        if(!empty($phone)){
            $vp = MCHelper::telephoneNumber($phone,true);
            if($vp['code'] == 0){
                return [
                    'code' => 0,
                    'result' => [
                        'msg' => $vp['msg']
                    ]
                ];
//                return redirect()->back()->withErrors($vp['msg'])->withInput();
            }
        }
        $user = User::query()->where('phone',$phone)->first();

        if (!$user)
            $user = User::query()->create([
                'username'=>$request->get('phone'),
//                'password'=>bcrypt($request->get('phone')),
                'phone'=>$request->get('phone'),
                'roles'=>'user'
            ]);
        if ($user->status != 1)
            return [
                'code' => 0,
                'result' => [
                    'msg' => '该用户已被锁定'
                ]
            ];
//            return redirect()->back()->withErrors(["该用户已被锁定"])->withInput();

        $code = $request->get('code');
        $appName = strtoupper(config('mbcore_mcore.app_name'));
        $cacheKey = md5('SMS'.$phone.$appName.'-LOGIN');
        $captcha = \Cache::get($cacheKey);
        if(empty($captcha)){
            return [
                'code' => 0,
                'result' => [
                    'msg' => '验证码已失效'
                ]
            ];
//            return redirect()->back()->withErrors('验证码已失效')->withInput();
        }
        if($code != $captcha){
            return [
                'code' => 0,
                'result' => [
                    'msg' => '验证码错误'
                ]
            ];
//            return redirect()->back()->withErrors('验证码错误')->withInput();
        }

        \Cache::forget($cacheKey);

        //获取用户登录时间,IP储存
        $loginTime = Carbon::now();
        $loginIP = $_SERVER['REMOTE_ADDR'];

        //将用户登录时间,IP储存
        User::query()->where('id',$user->id)->update([
            'last_login_time' => $loginTime,
            'last_login_ip' => $loginIP,
        ]);
        //将用户信息闪存入session
        session(['user.uid' => $user->id]);
        session(['user.username' => $user->username]);
        session(['user.phone' => $user->phone]);
        session(['user.is_login' => true]);

        //设置权限信息
        $this->setUserRolesSession($request,$user->roles);
        return [
            'code' => 1,
            'url' => route('user.default')
        ];
//        return redirect()->route('user.default');
    }
}
<?php

namespace Jenson\BaseUser\Controllers;

use Illuminate\Http\Request;

use Jenson\BaseUser\Models\Menu;
use Jenson\BaseUser\Models\User;

use Jenson\BaseUser\Libraries\Helper;
use Jenson\MCore\Libraries\Helper as MCHelper;

class UserController extends BaseController
{
    //demo
    public function rolestest(Request $request){
        if(Helper::hasVisterRoles($request)){
            dd("有权访问！");
        }else{
            dd("无权访问！");
        }
    }

    public function index(Request $request)
    {
        // test
        //echo Helper::getUserHome($request);

        $status = "home";

        //初始化菜单

        //顶级菜单
        $menus = Menu::where('parent_id',0)->orderBy('sort', 'desc')->get(["id","name","link","sort","parent_id","group_id","i_ico_class"])->toArray();
        //临时菜单数组
        $tempMenus = [];
        //重新整理数据
        foreach ($menus as $menu){
            $tempMenus[$menu["id"]] = $menu;
            $tempMenus[$menu["id"]]['hasChild'] = 0;
            $tempMenus[$menu["id"]]['isFather'] = 1;
        }
        //子菜单数据
        $subMenus = Menu::where('parent_id','<>',0)->orderBy('sort', 'desc')->get(["id","name","link","sort","parent_id",'group_id'])->toArray();
        foreach ($subMenus as $menu){
            $tempMenus[$menu["parent_id"]]['nodes'][] = $menu;
            $tempMenus[$menu["parent_id"]]['hasChild'] = 1;
        }

        if(config('mbcore_baseuser.baseuser_menuGroup')){
            $echoMenus = [];
            foreach($tempMenus as $menu){
                $echoMenus[$menu["group_id"]][] = $menu;
            }
            //dd($echoMenus);
        }else{
            $echoMenus = $tempMenus;
        }


        //读取数据库的权限信息
        $this->setRolesArr($request);

        return view('mbcore.baseuser::user.index',['status' => $status,'menus'=>$echoMenus]);
    }

    public function home()
    {
        $adminHome = 'mbcore.baseuser::user.home';
        if(!empty(config('mbcore_baseuser.baseuser_homeView'))){
            $adminHome = config('mbcore_baseuser.baseuser_homeView');
            //dd($adminHome);
        }
        return view($adminHome);
    }

    public function add(Request $request){

        //读取数据库的权限信息
        //$this->setRolesArr($request);
        return view('mbcore.baseuser::user.add');
    }

    public function addsave(Request $request)
    {
        $validator = \Validator::make($request->all(),[
            'username' => 'required',
            'password' => 'required',
            'fullName' => 'required',
            'email' => 'required|email',
            'status' => 'numeric',
        ], [
            'username.required' => '用户名不能为空',
            'password.required' => '密码不能为空',
            'fullName.required' => '姓名不能为空',
            'email.required' => '邮箱不能为空',
            'email.email' => '邮箱格式不正确',
            'status.numeric' => '状态字段异常',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        //dd("test");

        // 判断用户名或邮箱是否存在
        $adminCount = User::where('username',$request->username)
            ->orWhere('email', $request->email)
            ->count();
        if ($adminCount > 0){
            return redirect()->back()->withErrors("用户名或邮箱已存在，不可重复添加。")->withInput($request->all());
        }

        // 保存菜单数据
        $data = New User;
        $data->username = $request->username;
        $data->password = bcrypt($request->password); //加密处理
        $data->fullName = $request->fullName;
        $data->email = $request->email;
        $data->status = $request->status;
        $data->save();

        return redirect()->back()->withErrors("is_save_success")->withInput([]);
    }

    public function list(Request $request){
        $admins = User::get();
        //dd($admins)

        // 权限
        $system_roles = $this->getSystemRoles();
        //dd($system_roles);
        $menu_roles = $this->getMenuRoles();
        //dd($menu_roles);

        //当前路由的名称
        //$routeAction = $request->route()->getAction();
        //dd($routeAction);

        $roles = ['system'=>$system_roles,'menu'=>$menu_roles];
        //dd($roles);
        $rolesJson = json_encode(array_values($roles));

        //读取数据库的权限信息
        $this->setRolesArr($request);
        return view('mbcore.baseuser::user.list',['data'=>$admins,'rolesJson'=>$rolesJson]);
    }

    // Api  管理员编辑
    public function editsave(Request $request){

        $validator = \Validator::make($request->all(),[
            'id' => 'required',
            'fullName' => 'required',
            'email' => 'required|email',
        ], [
            'fullName.required' => '姓名不能为空',
            'email.required' => '邮箱不能为空',
            'email.email' => '邮箱格式不正确',
        ]);
        if ($validator->fails()) {
            //return redirect()->back()->withErrors($validator)->withInput();
            $warnings = $validator->messages();
            $show_warning = $warnings->first();
            return $this->retErr($show_warning);
        }

        $admin = User::find($request->id);
        if(!$admin){
            return $this->retErr("操作异常，此ID账号不存在。");
        }
        $admin->fullName = $request->fullName;
        $admin->email = $request->email;
        if(!empty($request->password)){
            $admin->password = bcrypt($request->password);
        }

        // 保存
        try{
            $admin->save();
            return $this->ret("修改成功");
        }catch (\Exception $e){
            return $this->retErr($e->getMessage());
        }


    }

    //Api  管理员权限获得
    public function getRole(Request $request){
        $admin_id = intval($request->id);
        if(empty($admin_id)){
            return $this->retErr('参数异常！');
        }
        $admin = User::find($admin_id);
        if(!$admin){
            return $this->retErr("操作异常，此ID账号不存在。");
        }
        //正常返回
        $roles = $admin->roles;
        if(!$roles) {
            $roles = [
                'system'=>[],
                'menu'=>[]
            ];
            $roles = json_encode($roles);
        }
        return $this->ret($roles);
    }

    //Api 保存管理员权限
    public function saveRole(Request $request){
        //验证管理员
        $admin_id = intval($request->id);
        if(empty($admin_id)){
            return $this->retErr('参数异常！');
        }
        $admin = User::find($admin_id);
        if(!$admin){
            return $this->retErr("操作异常，此ID账号不存在。");
        }
        //进行逻辑处理
        $systemRoles = $request->get("systemRoles",[]);
        $menuRoles = $request->get("menuRoles",[]);
        $data = [
            'system'=>$systemRoles,
            'menu'=>$menuRoles
        ];
        $dataJson = json_encode($data);

        $admin->roles = $dataJson;
        // 保存
        try{
            //刷新设置session，更新权限验证内容
            $this->setUserRolesSession($request,$dataJson);

            $admin->save();
            return $this->ret("修改成功");
        }catch (\Exception $e){
            return $this->retErr($e->getMessage());
        }

        //return $this->ret($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * Personal DAta
     */
    public function data(Request $request)
    {
        $id = session('user.uid');
        $data = User::query()->find($id);

        $subtitle = '个人资料';
        $compact = compact('data','subtitle');
        return view('mbcore.baseuser::personal.data',$compact);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     *
     * Change Password
     */
    public function changePassword(Request $request){
        $uid = session('user.uid');
        $user = User::query()->find($uid);

        if($request->isMethod('post')){
            $old_password = $request->get('old_password',null);
            if($old_password) {
                if (!password_verify($old_password,$user->password)) {
                    return redirect()->back()->withErrors('原密码不正确')->withInput();
                }
            }
            $new_password = $request->get('new_password',null);
            $confirm_password = $request->get('confirm_password',null);
            if($new_password && $confirm_password){
                if($confirm_password !=$new_password){
                    return redirect()->back()->withErrors('新密码和确认密码不一致')->withInput();
                }
            }
            $validator = \Validator::make($request->all(),[
                'old_password' => 'required',
                'new_password' => 'required',
                'confirm_password' => 'required',
            ], [
                'old_password.required' => '请输入原密码',
                'new_password.required' => '请输入新密码',
                'confirm_password.required' => '请输入确认密码',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            \DB::beginTransaction();
            try{
                $user->password = bcrypt($new_password);
                $user->save();

                \DB::commit();
                return redirect()->back()->withErrors("success")->withInput();
            }Catch(\Exception $e){
                \DB::rollBack();

                throw new \Exception($e->getMessage());
            }

        }
        $subtitle = '修改密码';
        $compact = compact('subtitle');
        return view('mbcore.baseuser::personal.changePassword',$compact);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * Forgot Password
     */
    public function forgotPassword(Request $request){
        $type = $request->get('type','captcha');
        if($request->isMethod('post')){

            if($type == 'captcha'){ // 验证用户名
                $captcha = $this->captcha($request);
                if($captcha['code'] == 0){
                    return redirect()->back()->withErrors($captcha['validator'])->withInput();
                }

                return redirect()->back()->withErrors("success")->withInput();

            }else if($type =='phone'){ // 手机号验证
                $phone = $this->phone($request);
                if($phone['code'] == 0){
                    return redirect()->back()->withErrors($phone['validator'])->withInput();
                }

                return redirect()->back()->withErrors("success")->withInput();

            }else if($type =='password'){ // 重置密码
                $password = $this->password($request);
                if($password['code'] == 0){
                    return redirect()->back()->withErrors($password['validator'])->withInput();
                }

                return redirect()->back()->withErrors("success")->withInput();
            }
        }

        $username = $request->get('username',null);
        if($type =='captcha')

            return view('mbcore.baseuser::personal.forgotPassword_cacaptcha',compact('username'));

        if($type =='phone')
            return view('mbcore.baseuser::personal.forgotPassword_phone',compact('username'));

        if($type =='password')
            $phone = $request->get('phone',null);
        return view('mbcore.baseuser::personal.forgotPassword_password',compact('phone'));
    }

    /**
     * @param $request
     * @return array
     *
     * Captcha/Username Validator
     */
    private function captcha($request){
        $username = $request->get('username',null);
        $user = User::query()->where('username',$username)->first();
        if (!$user)
            return [
                'code'=>0,
                'validator'=>'用户名不存在'
            ];
        $validator = \Validator::make($request->all(),[
            'username' => 'required',
            'captcha' => 'required|captcha',
        ], [
            'username.required' => '请输入用户名',
            'captcha.required' => '请输入验证码',
            'captcha.captcha' => '验证码错误',
        ]);
        if ($validator->fails()) {
            return [
                'code'=>0,
                'validator'=>$validator
            ];
        }
        return ['code'=>1];
    }

    /**
     * @param Request $request
     * @return array
     *
     * Phone Validator
     */
    private function phone(Request $request){
        $phone = $request->get('phone',null);
        if(!empty($phone)){
            $vp = MCHelper::telephoneNumber($phone,true);
            if($vp['code'] == 0){
                return [
                    'code'=>0,
                    'validator'=>$vp['msg']
                ];
            }
        }
        $user = User::query()->where('phone',$phone)->first();
        if (!$user)
            return [
                'code'=>0,
                'validator'=>'手机号未注册'
            ];


        $username = $request->get('username',null);
        if (!$username)
            return [
                'code'=>0,
                'validator'=>'缺少用户名信息'
            ];

        unset($user);
        $user = User::query()->where('username',$username)->first();
        if($user->phone != $phone){

            return [
                'code'=>0,
                'validator'=>'用户为'.$username.'的用户绑定的手机不是'.$phone
            ];
        }
        $validator = \Validator::make($request->all(),[
            'phone' => 'required',
            'code' => 'required',
        ], [
            'phone.required' => '手机号不能为空',
            'code.required' => '验证码不能为空'
        ]);

        if ($validator->fails()) {
            return [
                'code'=>0,
                'validator'=>$validator
            ];
        }

        $code = $request->get('code');
        $appName = strtoupper(config('mbcore_mcore.app_name'));
        $cacheKey = md5('SMS'.$phone.$appName.'-RESETPASSWORD');
        $captcha = \Cache::get($cacheKey);
        if(empty($captcha)){
            return [
                'code'=>0,
                'validator'=>'验证码已失效'
            ];
        }
        if($code != $captcha){
            return [
                'code'=>0,
                'validator'=>'验证码错误'
            ];
        }
        \Cache::forget($cacheKey);
        return ['code'=>1];
    }


    /**
     * @param $request
     * @return array
     * @throws \Exception
     *
     * Reset Password
     */
    private function password($request){
        $new_password = $request->get('new_password',null);
        $confirm_password = $request->get('confirm_password',null);
        if($new_password && $confirm_password){
            if($confirm_password !=$new_password){
                return [
                    'code'=>0,
                    'validator'=>'新密码和确认密码不一致'
                ];
            }
        }
        $validator = \Validator::make($request->all(),[
            'new_password' => 'required',
            'confirm_password' => 'required',
        ], [
            'new_password.required' => '请输入新密码',
            'confirm_password.required' => '请输入确认密码',
        ]);


        if ($validator->fails()) {
            return [
                'code'=>0,
                'validator'=>$validator
            ];
        }

        $phone = $request->get('phone',null);
        if(!$phone)
            return [
                'code'=>0,
                'validator'=>'缺少手机号信息'
            ];

        $user = User::query()->where('phone',$phone)->first();

        \DB::beginTransaction();
        try{
            $user->password = bcrypt($new_password);
            $user->save();

            \DB::commit();
            return ['code'=>1];
        }Catch(\Exception $exception){
            \DB::rollBack();
            throw new \Exception($exception->getMessage());
        }

    }
}
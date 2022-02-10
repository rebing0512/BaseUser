<?php
namespace Jenson\BaseUser\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Jenson\BaseUser\Models\User;
use Jenson\BaseUser\Models\Menu;

use Jenson\BaseUser\Libraries\Helper;
use Jenson\MCore\Libraries\Helper as CoreHelper;

class BaseController extends Controller
{


    public function __construct(Request $request)
    {
        parent::__construct($request);
        if(config('mbcore_baseuser.baseuser_homeRoute'))
            $adminHomeRoute= config('mbcore_baseuser.baseuser_homeRoute');
        else
            $adminHomeRoute= 'user.home';
        view()->share('adminHomeRoute',$adminHomeRoute );

        CoreHelper::LoadConfig();
    }
    /**
     * @param $msg
     * @param int $code
     * @param int $httpCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function ret($msg,$code=1, $httpCode=200)
    {
        return response()->json([
            'code' => $code,
            'result' => $msg
        ],$httpCode,[],271);
    }

    /**
     * @param $msg
     * @param int $code
     * @param int $httpCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function retErr($msg,$code=0, $httpCode=200)
    {
        return $this->ret($msg,$code, $httpCode);
    }


    // 系统级别权限
    public function getSystemRoles(){
        return Helper::getSystemRoles();
    }
    // 菜单级别权限
    public function getMenuRoles(){
        return Helper::getMenuRoles();
    }


    public function setRolesArr(Request $request){
        //权限渲染菜单输出数据【每次刷新页面进行校验】
        $rolesData = User::find($request->session()->get("user.uid"));
//        $rolesData = User::find(session("user.uid"))->toArray();
        $rolesJson = $rolesData["roles"];
        if($rolesJson != "is_super_user"){
            $rolesArr = json_decode($rolesJson,true);
        }else{
            $rolesArr = ['system'=> $rolesJson,'menu'=> $rolesJson];
        }
        view()->share('rolesArr',$rolesArr );
        //dd(gettype($rolesArr));
        //刷新设置session，更新权限验证内容
        $this->setUserRolesSession($request,$rolesJson);
    }

    //设置用户的session
    public function setUserRolesSession(Request $request,$rolesJson){
        if($rolesJson != "is_super_user"){
            if(is_array($rolesJson)){
                $rolesArr = json_decode($rolesJson,true);
                $tempMuenId = [];
                foreach($rolesArr['menu'] as $val){
                    if(!strstr($val,"G")){
                        $tempMuenId[] = $val;
                    }
                }
                $MenuRouteData = Menu::whereIn('id', $tempMuenId)->get()->toArray();
                $MenuRoute = [];
                foreach($MenuRouteData as $val){
                    $MenuRoute[md5($val['link'])] = $val['link'];
                }
                $MenuRouteSession = json_encode(array_values($MenuRoute));
                //dd($MenuRouteSession);
                session(['roles' => $MenuRouteSession]);
            }else{
                session(['roles' => $rolesJson]);
            }

        }else{
            session(['roles' => "is_super_user"]);
        }
    }
}
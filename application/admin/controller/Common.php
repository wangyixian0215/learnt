<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-10-12 0012
 * Time: 21:17
 */
namespace app\admin\controller;



use app\admin\service\AdminService;
use think\Controller;
use think\facade\Cookie;
use think\facade\Session;
use think\facade\View;

class Common extends Controller{
    public function __construct()
    {
        parent::__construct();
        $cookie=Cookie::get("admin");
        $session=Session::get("admin");
        if($cookie && !$session){
            Session::set("admin",$cookie);
        }
        if(!Session::has("admin")){
            $this->success("请先登录","login/login");
        }

        if(!$this->nodeChecked()){
            if(request()->isAjax()){
                return ["status"=>-1,"msg"=>"没有权限操作"];
            }
            //echo "no";
            $this->success("您没有权限访问哦",'index/index');
        }else{
            //展示菜单
            $menueService=new AdminService();
            $menue=$menueService->getMenue();
            $menueTree=$menueService->getMenueTree($menue);
            //dump($menueTree);
            View::share("menue",$menueTree);

        }
    }
    //检查权限
    public function nodeChecked(){
        $admin_name=Session::get("admin")['admin_name'];
        //如果是超级管理员则所有的都能访问
        if(in_array($admin_name,config('web.super_admin'))){
            return true;
        }
        //当前访问路径
        $access=ucfirst(strtolower(request()->controller()))."/".strtolower(request()->action());
        //判断是否需要检查权限
        if(in_array($access,config("web.nocheck"))){
            return true;
        }
        //获取当前登录用户拥有的权限
        $adminNode=new AdminService();
        $myAccess=$adminNode->getAdminNode();
        if(in_array($access,$myAccess)){
            return true;
        }else{
            return false;
        }


    }
}
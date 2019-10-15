<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-10-12 0012
 * Time: 21:17
 */
namespace app\admin\controller;



use think\Controller;
use think\facade\Cookie;
use think\facade\Session;

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
    }
}
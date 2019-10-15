<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-10-12 0012
 * Time: 11:30
 */
namespace app\admin\controller;

use app\admin\validate\Admin;
use think\captcha\Captcha;
use think\Controller;
use think\facade\Cookie;
use think\facade\Request;
use think\facade\Session;
use think\Validate;


class Login extends Controller
{
    public function login()
    {
        if(request()->isGet()){
            return view();
        }
        if(request()->isPost()) {
            config("app_debug",false);
            config("app_trace",false);
            config("debug",false);
            $admin_name = request()->post("admin_name", "");
            $admin_pwd = request()->post("admin_pwd", "");
            $code = request()->post("code", "");
            $re = request()->post("re","");
            //验证数据
            $data = [
                "admin_name" => $admin_name,
                "admin_pwd" => $admin_pwd,
                "code" => $code
            ];
            //验证验证码
            if( !captcha_check($code )){
                echo json_encode(["status"=>2,"msg"=>"验证码错误"]);
                exit;
            }
            $result=$this->validate($data,'app\admin\validate\Admin.login');
            if($result!==true){
                echo json_encode(["status"=>3,"msg"=>$result]);
                exit;
            }
            //验证登录账号
            $where = ["admin_name" => $admin_name];
            $admin_salt = \app\admin\model\Admin::getSalt($where);
            $admin_pwd=md5(md5($admin_pwd).$admin_salt['admin_salt']);
            $where=["admin_name"=>$admin_name,"admin_pwd"=>$admin_pwd];
            $admin=\app\admin\model\Admin::getAdmin($where);
            //var_dump($admin);
            //添加ip和time
            $data=["admin_login_ip"=>$_SERVER['REMOTE_ADDR'],"admin_login_time"=>time()];
            $adminUpdate=\app\admin\model\Admin::updateAdmin($data,$where);
            //七天免登陆
            if($re==1){
                Cookie::set("admin",$admin,7*24*3600,"/tpshop");
            }
            //var_dump($admin);
            Session::set("admin",$admin);
            //返回数据
            if($admin){
                echo json_encode(["status"=>1,"msg"=>"ok"]);
            }else{
                echo json_encode(["status"=>0,"msg"=>"no"]);
            }

        }

    }
    public function loginCheck(){
        config("app_debug",false);
        config("app_trace",false);
        config("debug",false);
        $admin_name = request()->post("admin_name", "");
        $admin_pwd = request()->post("admin_pwd", "");
        $where = ["admin_name" => $admin_name];
        $admin_salt = \app\admin\model\Admin::getSalt($where);
        $admin_pwd=md5(md5($admin_pwd).$admin_salt['admin_salt']);
        $where=["admin_name"=>$admin_name,"admin_pwd"=>$admin_pwd];
        $admin=\app\admin\model\Admin::getAdmin($where);
        if($admin){
            echo json_encode(["status"=>1,"msg"=>"ok"]);
        }else{
            echo json_encode(["status"=>0,"msg"=>"no"]);
        }
    }
    public function code(){
        $config =    [
            // 验证码字体大小
            'fontSize'    =>    30,
            // 验证码位数
            'length'      =>    3,
            'useCurve'   =>  false,
            // 关闭验证码杂点
            'useNoise'    =>    false,
            'bg'=>[92,189,170]

        ];
        $code=new Captcha($config);
        return $code->entry();
    }
}
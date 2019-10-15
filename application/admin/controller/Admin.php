<?php

namespace app\admin\controller;

use app\admin\model\Log;
use app\admin\model\Role;
use think\Controller;
use think\facade\Session;
use think\Request;

class Admin extends Common
{
    public function index()
    {
        //查询admin
        $adminModel=new \app\admin\model\Admin();
        $admin=$adminModel->all();
        return view()->assign("admin",$admin);

    }
    public function add(){
        if(request()->isGet()){
            //查询角色
            $role=Role::all();
            return view()->assign("role",$role);
        }
        if(request()->isPost()){
            $admin=request()->post();
            //验证数据
            $result=$this->validate($admin,'app\admin\validate\Admin.add');
            if($result!==true){
                echo json_encode(["status"=>0,"msg"=>$result]);
                exit;
            }
          unset($admin['admin_repwd']);
            $admin['admin_salt']=substr(uniqid(),-4);
            $admin['admin_pwd']=md5(md5($admin['admin_pwd']).$admin['admin_salt']);
            $admin['admin_add_time']=time();
            //入库
            $adminModel=new \app\admin\model\Admin();
            if($adminModel->save($admin) && $adminModel->role()->save($admin['role_id'])){
                $logModel=new Log();
                $log=[
                    "admin_id"=>\think\facade\Session::get("admin")['admin_id'],
                    "admin_ip"=>$_SERVER['REMOTE_ADDR'],
                    "log_content"=>"添加了".$admin['admin_name']."管理员",
                    "log_time"=>time()
                ];
                $logModel->save($log);
                echo json_encode(["status"=>1,"msg"=>"ok"]);
            }else{
                echo json_encode(["status"=>2,"msg"=>"添加数据失败"]);
            }
        }
    }
    public function update(){
        if(request()->isGet()){
            $role=Role::all();
            $admin_id=request()->get("admin_id");
            //查权限
            $adminModel=new \app\admin\model\Admin();
            $admin=$adminModel->get($admin_id);

            return view()->assign("admin",$admin)->assign("role",$role);
        }
        if(request()->isPost()){
            $admin=request()->post();
            //修改数据
            if(isset($admin['role_id'])){
                $admin['role_id']=implode(",",$admin['role_id']);
            }
            $admins=\app\admin\model\Admin::updateRole($admin);
            if($admins){
                $logModel=new Log();
                $log=[
                    "admin_id"=>\think\facade\Session::get("admin")['admin_id'],
                    "admin_ip"=>$_SERVER['REMOTE_ADDR'],
                    "log_content"=>"修改了".$admin['admin_name']."管理员权限",
                    "log_time"=>time()
                ];
                $logModel->save($log);
                echo json_encode(["status"=>1,"msg"=>"ok"]);
            }else{
                echo json_encode(["status"=>2,"msg"=>"修改数据失败"]);
            }
        }
    }
    public function delAdmin(){
        $admin_id=intval(request()->post("admin_id",0));
        if($admin_id==0){
            $this->error("非法请求");
        }
        //删除
        $adminModel=new \app\admin\model\Admin();
        $admin=$adminModel->get($admin_id);
        if($admin->delete()){
            //添加日志
            $logModel=new Log();
            $log=[
                "admin_id"=>\think\facade\Session::get("admin")['admin_id'],
                "admin_ip"=>$_SERVER['REMOTE_ADDR'],
                "log_content"=>"删除".$admin['admin_name']."管理员",
                "log_time"=>time()
            ];
            $logModel->save($log);
            echo json_encode(["status"=>1,"msg"=>"ok"]);
        }else{
            echo json_encode(["status"=>0,"msg"=>"no"]);
        }
    }










}

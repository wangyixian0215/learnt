<?php

namespace app\admin\controller;

use app\admin\model\Log;
use app\admin\model\Role;
use think\Controller;
use think\facade\Session;
use think\Request;

class Admin extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function showAdmin()
    {
        //查询admin
        $admin=\app\admin\model\Admin::select();
        return view()->assign("admin",$admin);

    }
    public function addAdmin(){
        if(request()->isGet()){
            //查询角色
            $role=Role::select();
            return view()->assign("role",$role);
        }
        if(request()->isPost()){
            $admin=request()->post();
            //var_dump($admin);
            //验证数据
            $result=$this->validate($admin,'app\admin\validate\Admin.add');
            if($result!==true){
                echo json_encode(["status"=>0,"msg"=>$result]);
                exit;
            }
          unset($admin['admin_repwd']);
            if(isset($admin['role_id'])){
                $admin['role_id']=implode(",",$admin['role_id']);
            }
            //var_dump($admin);
            //入库
            if($admin=\app\admin\model\Admin::addAdmin($admin)){
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
    public function updateAdmin(){
        if(request()->isGet()){
            $role=Role::select();
            $admin_id=request()->get("admin_id");
            //查权限
            $admin=\app\admin\model\Admin::getRole($admin_id);
            //var_dump($admin);
            $admin['role_id']=explode(",",$admin['role_id']);
            foreach($admin['role_id'] as $k=>$v){
                $admin['role_name'][]=$v;
            }
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










}

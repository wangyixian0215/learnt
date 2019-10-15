<?php

namespace app\admin\controller;

use app\admin\model\Log;
use app\admin\model\Node;
use think\Controller;
use think\Request;

class Role extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //查询数据
        $role=\app\admin\model\Role::role();
        foreach($role as $k=>$v){
            $role[$k]['node_id']=explode(",",$v['node_id']);
        }
        foreach($role as $key=>$val){
            foreach($val['node_id'] as $k=>$v){
                //查权限
                $role[$key]['node_name'][]=Node::roleNode($v);
            }
        }
        return view()->assign("role",$role);
    }
    public function add(){
        if(request()->isGet()){
            //查询权限
            $node=Node::showNode();
            $node=Node::getAllNode($node);
            //var_dump($node);
            return view()->assign("node",$node);
        }
        if(request()->isPost()){
            $role=request()->post();
            //验证数据
            $result=$this->validate($role,'app\admin\validate\Role');
            if($result!==true){
                echo json_encode(["status"=>0,"msg"=>$result]);
                exit;
            }
            //var_dump($role['node_id']);
            if(isset($role['node_id'])){
                $role['node_id']=implode(",",$role['node_id']);
            }
            //入库
            if($role=\app\admin\model\Role::addRole($role)){
                //var_dump($role);
                $logModel=new Log();
                $log=[
                    "admin_id"=>\think\facade\Session::get("admin")['admin_id'],
                    "admin_ip"=>$_SERVER['REMOTE_ADDR'],
                    "log_content"=>"添加了".$role['role_name']."角色",
                    "log_time"=>time()
                ];
                $logModel->save($log);
                echo json_encode(["status"=>1,"msg"=>"ok"]);
            }else{
                echo json_encode(["status"=>2,"msg"=>"添加数据失败"]);
            }
        }

    }








}

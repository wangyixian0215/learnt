<?php

namespace app\admin\controller;

use app\admin\model\Log;
use app\admin\model\Node;
use app\admin\service\NodeService;
use think\Controller;
use think\Request;

class Role extends Common
{
    public function index()
    {
        //查询数据
        $roleModel=new \app\admin\model\Role();
        $role=$roleModel->all();
        return view()->assign("role",$role);
    }
    public function add()
    {
        if (request()->isGet()) {
            //查询权限
            $node=new NodeService();
            $node=$node->getNodeTree(Node::all());
            return view()->assign("node", $node);
        }
        if (request()->isPost()) {
            $role = request()->post();
            //验证数据
            $result = $this->validate($role, 'app\admin\validate\Role');
            if ($result !== true) {
                echo json_encode(["status" => 0, "msg" => $result]);
                exit;
            }
            //入库
            $roleModel=new \app\admin\model\Role();
            if($roleModel->save($role) && $roleModel->node()->saveAll($role['node_id'])){
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
    public function update(){
        echo "我是角色修改";
    }
    public function delete(){
        echo "我是角色删除";
    }

}

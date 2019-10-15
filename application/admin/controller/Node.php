<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-10-14 0014
 * Time: 11:58
 */
namespace app\admin\controller;
use app\admin\model\Log;

class Node extends Common
{
    public function showNode()
    {
        //查询权限
        $node=\app\admin\model\Node::showNode();
        $orderNode=\app\admin\model\Node::orderNode($node);
        //var_dump($orderNode);
        return view()->assign("node",$orderNode);
    }
    public function addNode(){
        if(request()->isGet()){
            //查询权限名称
            $node=\app\admin\model\Node::showNode();
            $orderNode=\app\admin\model\Node::orderNode($node);
            //var_dump($orderNode);
            return view()->assign("orderNode",$orderNode);
        }
        if(request()->isPost()){
            $node=request()->post();
            //var_dump($node);
            //验证数据
            $result=$this->validate($node,'app\admin\validate\Node');
            if($result!==true){
                $this->error($result);
            }
            //入库
            if($node=\app\admin\model\Node::addNode($node)){
                $logModel=new Log();
                $log=[
                    "admin_id"=>\think\facade\Session::get("admin")['admin_id'],
                    "admin_ip"=>$_SERVER['REMOTE_ADDR'],
                    "log_content"=>"添加了".$node['node_name']."权限",
                    "log_time"=>time()
                ];
                $logModel->save($log);
                $this->success("添加权限成功","showNode");
            }else{
                $this->error("添加权限失败");
            }

        }
    }
}
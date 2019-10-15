<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-10-14 0014
 * Time: 11:58
 */
namespace app\admin\controller;
use app\admin\model\Log;
use app\admin\service\NodeService;

class Node extends Common
{
    public function index()
    {
        //查询权限
        $node=new NodeService();
        $orderNode=$node->getNodeOrder(\app\admin\model\Node::all());
        return view()->assign("node",$orderNode);
    }
    public function add(){
        if(request()->isGet()){
            //查询权限名称
            $node=new NodeService();
            $orderNode=$node->getNodeOrder(\app\admin\model\Node::all());
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
            $nodeModel=new \app\admin\model\Node();
            if($nodes= $nodeModel->save($node)){
                $logModel=new Log();
                $log=[
                    "admin_id"=>\think\facade\Session::get("admin")['admin_id'],
                    "admin_ip"=>$_SERVER['REMOTE_ADDR'],
                    "log_content"=>"添加了".$node['node_name']."权限",
                    "log_time"=>time()
                ];
                $logModel->save($log);
                $this->success("添加权限成功","index");
            }else{
                $this->error("添加权限失败");
            }
        }
    }
    public function update(){
        echo "权限修改";
    }
    public function delete(){
        echo "权限删除";
    }
}
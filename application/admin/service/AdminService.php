<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-10-15 0015
 * Time: 23:18
 */
namespace app\admin\service;
use app\admin\model\Admin;
use app\admin\model\AdminRole;
use app\admin\model\Node;
use app\admin\model\Role;
use think\facade\Session;

class AdminService{
    public function getAdminNode(){
        $adminRole=new AdminRole();
        $role_id=$adminRole->where("admin_id",Session::get("admin")['admin_id'])->column("role_id");
        $roleModel=new Role();
        $role=$roleModel->all($role_id);
        $node=[];
        foreach($role as $k=>$v){
            $node=array_merge($node,$v->node->toArray());
        }
        $myAccess=[];
        foreach($node as $k=>$v){
            array_push($myAccess,ucfirst(strtolower($v['node_controller'])."/".strtolower($v['node_action'])));
        }
        $myAccess=array_unique($myAccess);
        return $myAccess;
    }
    public function getMenue(){
        if(in_array(Session::get("admin")['admin_name'],config("web.super_admin"))){
            $menue=(new Node())->where("node_ismenue",1)->all()->toArray();
        }else{
            $admin=(new Admin())->get(Session::get("admin")['admin_id']);
            $menue=[];
            foreach ($admin->role as $k=>$v ){
                $menue=array_merge($menue,$v->node->where("node_ismenue",1)->toArray());
            }
        }
        return $menue;
    }
    public function getMenueTree($menue,$pid=0){
        $menueTree=[];
        foreach($menue as $k=>$v){
            if($v['node_pid']==$pid){
                if($child=$this->getMenueTree($menue,$v['node_id'])){
                    $v['child'][]=$child;
                }
                $menueTree[]=$v;
            }
        }
       return $menueTree;
    }

}
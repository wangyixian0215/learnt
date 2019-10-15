<?php

namespace app\admin\model;

use think\Db;
use think\Model;

class Node extends Model
{
    protected $pk="node_id";
    //查询权限
    public static function showNode(){
        return self::select();
    }
    //两级权限
    public static function orderNode($node,$level=0,$pid=0){
        static $orderNode=[];
        foreach($node as $key=>$val){
            if($val['node_pid']==$pid){
                $val['node_level']=$level;
                $orderNode[]=$val;
                self::orderNode($node,$level+1,$val['node_id']);
            }
        }
        return $orderNode;
    }

    //添加权限
    public static function addNode($node){
        return self::insert($node);
    }
    public static function getAllNode($node, $pid = 0)
    {
        $allNode = [];
        foreach ($node as $k => $v) {
            if ($v['node_pid'] == $pid) {
                //往数组中加入一个child字段
                $v['child'] = Node::getAllNode($node,$v['node_id']);
                $allNode[] = $v;
            }
        }
        return $allNode;
    }
    //查权限
    public static function roleNode($v){
        return self::where(['node_id'=>$v])->field(['node_name'])->select()->toArray();
    }
}

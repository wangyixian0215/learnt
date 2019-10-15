<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-10-15 0015
 * Time: 22:04
 */
namespace app\admin\service;
class NodeService{
    public function getNodeOrder($nodes,$node_pid=0,$level=0){
        $nodeOrder=[];
        foreach($nodes as $key=>$val){
            if($val->node_pid==$node_pid){
                $val->level=$level;
                array_push($nodeOrder,$val);
                $nodeOrder=array_merge($nodeOrder,$this->getNodeOrder($nodes,$val->node_id,$level+1));
            }
        }
        return $nodeOrder;
    }
//    public function getNodeOrder($nodes,$node_pid=0,$level=0){
//        $nodeOrder=[];
//        foreach($nodes as $key=>$val){
//            if($val->node_pid==$node_pid){
//                $val->level=$level;
//                array_push($nodeOrder,$val);
//                $nodeOrder=array_merge($nodeOrder,$this->getNodeOrder($nodes,$val->node_id,$level+1));
//            }
//        }
//        return $nodeOrder;
//    }
    public function getNodeTree($nodes,$node_pid=0){
        $nodeTree=[];
        foreach($nodes as $key=>$val){
            if($val->node_pid==$node_pid){
                $val->child=$this->getNodeTree($nodes,$val->node_id);
                $nodeTree[]=$val;
            }
        }
        return $nodeTree;
    }
}
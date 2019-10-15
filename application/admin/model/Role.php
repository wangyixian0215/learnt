<?php

namespace app\admin\model;

use think\Model;

class Role extends Model
{
    protected $pk='role_id';
    //模型关联
    public function node()
    {
        return $this->belongsToMany('Node');
    }
}

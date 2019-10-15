<?php

namespace app\admin\model;

use think\Model;

class Role extends Model
{
    protected $pk='role_id';
    public static function addRole($role){
        return self::insert($role);
    }
    public static function role(){
        return self::select()->toArray();
    }
    public static function adminRole($v){
        return self::where(['role_id'=>$v])->field(["role_name","role_id"])->select()->toArray();
    }
}

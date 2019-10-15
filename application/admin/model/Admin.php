<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-10-12 0012
 * Time: 16:37
 */
namespace app\admin\model;

use think\Db;
use think\Model;

class Admin extends Model{
    protected $pk = 'admin_id';
    public static function getSalt($where){
        return self::where($where)->field("admin_salt")->find();
    }
    public static function getAdmin($where){
        return self::where($where)->field(["admin_id","admin_name"])->find();
    }
    public static function updateAdmin($data,$where){
        return self::where($where)->update($data);
    }
    public static function admin(){
        return self::select();
    }
    public static function addAdmin($admin){
        return self::insert($admin);
    }
    public static function getRole($admin_id){
        return self::where(["admin_id"=>$admin_id])->find()->toArray();
    }
    public static function updateRole($admin){
        return self::where(["admin_id"=>$admin['admin_id']])->update($admin);
    }
}
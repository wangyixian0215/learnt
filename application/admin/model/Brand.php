<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-10-14 0014
 * Time: 8:50
 */
namespace app\admin\model;

use think\Db;
use think\Model;

class Brand extends Model{
    protected $pk = "brand_id";
    public static function addBrand($brand){
        return self::insert($brand);
    }
    public static function brand(){
        return self::select();
    }
}
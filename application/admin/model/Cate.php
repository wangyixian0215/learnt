<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-10-12 0012
 * Time: 22:52
 */
namespace app\admin\model;

use think\Db;
use think\Model;

class Cate extends Model
{
    protected $pk = 'cate_id';

    //查所有分类
    public static function getCate()
    {
        $cate = self::select();
        return $cate;
    }

    //查无极限分类
    public static function cateOrder($cate, $pid = 0, $lever = 0)
    {
        static $cateOrder = [];
        foreach ($cate as $key => $val) {
            if ($val['cate_pid'] == $pid) {
                $val['lever'] = $lever;
                $cateOrder[] = $val;
                self::cateOrder($cate, $val['cate_id'], $lever + 1);
            }
        }
        return $cateOrder;
    }

    //添加分类
    public static function addCate($cate)
    {
        return self::insert($cate);
    }

    //查询顶级分类
    public static function getFirstCate()
    {
        return self::where(["cate_pid" => 0])->select();
    }
    //查询修改的数据
    public static function updateCate($cate_id){
        return self::where(["cate_id"=>$cate_id])->find();
    }
    //修改数据
    public static function updateCates($cate){
        return self::where(["cate_id"=>$cate['cate_id']])->update($cate);
    }
    //删除分类
    public static function delCate($cate_id){
        return self::where(["cate_id"=>$cate_id])->delete();
    }

}
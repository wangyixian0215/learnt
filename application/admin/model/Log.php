<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-10-13 0013
 * Time: 17:27
 */
namespace app\admin\model;

use think\Db;
use think\Model;
class Log extends Model{
    protected $pk = 'log_id';
    public static function addLog($log){
        return self::insert($log);
    }
}

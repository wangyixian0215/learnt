<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-10-13 0013
 * Time: 0:03
 */
namespace app\admin\validate;
use think\Validate;
class Cate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        "cate_name"=>"require|regex:/^.{1,12}$/",
        "cate_alias"=>"require|regex:/^.{1,12}$/",
        "cate_order"=>"require"
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [
        'cate_name.require'=>'分类名称不能为空',
        'cate_name.regex'=>'分类名称为1-12',
        'cate_alias.require'=>'别名不能为空',
        'cate_alias.regex'=>'别名为1-12位',
        'cate_order.require'=>'排序不能为空'

    ];
}

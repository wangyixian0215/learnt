<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-10-13 0013
 * Time: 22:29
 */
namespace app\admin\validate;
use think\Validate;
class Brand extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        "brand_name"=>"require|unique:brand",
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [
        'admin_name.require'=>'品牌名称不能为空',
        'admin_name.unique'=>'品牌名称已存在',

    ];
}
<?php

namespace app\admin\validate;

use think\Validate;

class Node extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'node_name'=>'require|unique:node',
        'node_url'=>'require'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'node_name.require'=>"权限名称不能为空",
        'node_name.unique'=>'权限名称已经存在',
        'node_url,require'=>'权限路径不能为空'
    ];
}

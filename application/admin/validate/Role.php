<?php

namespace app\admin\validate;

use think\Validate;

class Role extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'role_name'=>'require|unique:role',
        'node_id'=>'require'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'role_name.require'=>"角色名称不能为空",
        'role_name.unique'=>'角色名称已经存在',
        'node_id'=>'权限不能为空'
    ];
}

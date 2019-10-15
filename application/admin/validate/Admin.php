<?php
namespace app\admin\validate;
use think\Validate;
class Admin extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        "admin_name"=>"require|regex:/^[a-zA-Z0-9]{3,12}$/",
        "admin_pwd"=>"require|regex:/^[a-zA-Z0-9]{6,12}$/",
        "admin_repwd"=>"require|confirm:admin_pwd",
        "admin_email"=>"email",
        "code"=>"require"
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'admin_name.require'=>'管理员不能为空',
        'admin_name.regex'=>'管理员必须在3-12位',
        'admin_pwd.require'=>'密码不能为空',
        'admin_pwd.regex'=>'密码的位数必须6-12位',
        'admin_repwd.require'=>"确认密码不能为空",
        'admin_repwd.confirm'=>"两次密码输入不相同",
        'admin_email.email'=>"邮箱格式错误",
        'code.require'=>'验证码不能为空'

    ];
    protected $scene = [
        'add'  =>  ['admin_name','admin_pwd','admin_repwd','admin_email'],
        'login'  =>  ['admin_name','admin_pwd','code'],
    ];

}

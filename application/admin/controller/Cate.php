<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\Session;
use think\Request;


class Cate extends Common
{
    public function index()
    {
        //查询所有分类
        $cate=\app\admin\model\Cate::getCate();
        $cate=\app\admin\model\Cate::cateOrder($cate);
        return view()->assign("cate",$cate);
    }
    public function add()
    {
        if(request()->isGet()){
            //查询分类
            $cate=\app\admin\model\Cate::getCate();
            //无限极分类
            $cateOrder=\app\admin\model\Cate::cateOrder($cate);
            return view()->assign("cateOrder",$cateOrder);
        }
        if(request()->isPost()){
            $cate=request()->post()?request()->post():"";
            //验证数据
            $result=$this->validate($cate,'app\admin\validate\Cate');
            if($result!==true){
                $this->error($result);
            }
            //入库
            if(\app\admin\model\Cate::addCate($cate)){
                //var_dump(Session::get("admin"));
                //添加日志
                $log=[

                    "admin_id"=>Session::get("admin")['admin_id'],
                    "admin_ip"=>$_SERVER['REMOTE_ADDR'],
                    "log_content"=>"添加".$cate['cate_name']."分类",
                    "log_time"=>time()
                ];

                \app\admin\model\Log::addLog($log);
                $this->success("添加分类成功","index");
            }else{
                $this->error("添加分类失败");
            }
        }
    }

    public function update()
    {
        if(request()->isGet()){
            $cate_id=intval(request()->get("cate_id",0));
            if($cate_id==0){
                $this->error("非法请求");
            }
            //查询分类
            $cate=\app\admin\model\Cate::getCate();
            //无限极分类
            $cateOrder=\app\admin\model\Cate::cateOrder($cate);
            //查询修改的数据
            $cate=\app\admin\model\Cate::updateCate($cate_id);
            //var_dump($cate);
            return view()->assign("cate",$cate)->assign("cateOrder",$cateOrder);
        }
        if(request()->isPost()){
            $cate=request()->post()?request()->post():"";
            //验证数据
            $result=$this->validate($cate,'app\admin\validate\Cate');
            if($result!==true){
                $this->error($result);
            }
            //入库
            if(\app\admin\model\Cate::updateCates($cate)){
                //添加日志
                $log=[
                    "admin_id"=>Session::get("admin")['admin_id'],
                    "admin_ip"=>$_SERVER['REMOTE_ADDR'],
                    "log_content"=>"修改".$cate['cate_name']."分类",
                    "log_time"=>time()
                ];
                \app\admin\model\Log::addLog($log);
                $this->success("修改分类成功","index");
            }else{
                $this->error("修改分类失败");
            }
        }
    }

    public function delete()
    {
        $cate_id=intval(request()->post("cate_id",0));
        if($cate_id==0){
            $this->error("非法请求");
        }
        $cate=\app\admin\model\Cate::updateCate($cate_id);
        //删除分类
        if(\app\admin\model\Cate::delCate($cate_id)){
            //添加日志
            $log=[
                "admin_id"=>Session::get("admin")['admin_id'],
                "admin_ip"=>$_SERVER['REMOTE_ADDR'],
                "log_content"=>"删除".$cate['cate_name']."分类",
                "log_time"=>time()
            ];
            \app\admin\model\Log::addLog($log);
            echo json_encode(["status"=>1,"msg"=>"ok"]);
        }else{
            echo json_encode(["status"=>0,"msg"=>"no"]);
        }
    }
}

<?php

namespace app\admin\controller;

use app\admin\model\Log;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use think\Controller;
use think\Request;

class Brand extends Common
{
    public function index()
    {
        //查询品牌
        $brand=\app\admin\model\Brand::brand();
        return view()->assign("brand",$brand);
    }

    public function add()
    {
        if(request()->isGet()){
            return view();
        }
        if(request()->isPost()){
            $brand=request()->post();
            //验证数据
            $result=$this->validate($brand,'app\admin\validate\Brand');
            if($result!==true){
                echo json_encode(["status"=>2,"msg"=>$result]);
                exit;
            }
            //上传到七牛云
            //判断文件大小、错误
            $brand_logo=$_FILES['brand_logo'];
            if ($brand_logo['name'] == "") {
                echo json_encode(["status" => 1, "msg" => "没有文件上传"]);
                exit;
            }
            if ($brand_logo['size'] > 2000000) {
                echo json_encode(["status" => 2, "msg" => "上传的头像有一点大哦"]);
                exit;
            }
            if ($brand_logo['error'] != 0) {
                echo json_encode(["status" => 3, "msg" => "上传头像错误，请重新上传"]);
                exit;
            }
            $accessKey = "l-wOlHSksAD81xQD5j-mJ-dHYSeNR6dag_lE-lrq";
            $secretKey = "FCuw240ErQwGgCBTQO_AGntCqHFIzfoVt3Z_Fc9_";
            $bucket = "wenda-face";
            // 构建鉴权对象
            $auth = new Auth($accessKey, $secretKey);
            // 生成上传 Token
            $token = $auth->uploadToken($bucket);
            // 要上传文件的本地路径
            $filePath = $brand_logo['tmp_name'];
            // 上传到七牛后保存的文件名
            $etc = pathinfo($brand_logo['name'], PATHINFO_EXTENSION);
            $key = uniqid() . "." . $etc;
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
            if ($err == null) {
                $path = "http://py0poq89o.bkt.clouddn.com/" . $ret['key'];
                //入库
                $brand['brand_logo']=$path;
                \app\admin\model\Brand::addBrand($brand);
                $logModel=new Log();
                $log=[
                    "admin_id"=>\think\facade\Session::get("admin")['admin_id'],
                    "admin_ip"=>$_SERVER['REMOTE_ADDR'],
                    "log_content"=>"添加了".$brand['brand_name']."品牌",
                    "log_time"=>time()
                ];
                $logModel->save($log);
                echo json_encode(["status" => 0, "msg" => "ok","path"=>$path]);
            } else {
                echo json_encode(["status" => 4, "msg" => "上传失败"]);
            }
        }

    }

    public function update()
    {
        if(request()->isGet()){
            //查询数据
            $brand_id=intval(request()->get("brand_id"))?request()->get("brand_id"):0;
            if($brand_id==0){
                echo json_encode(["status"=>4,"msg"=>"非法请求"]);
            }

            $brandModole=new \app\admin\model\Brand();
            $brand=$brandModole->get($brand_id)->toArray();
            return view()->assign("brand",$brand);
        }
        if(request()->isPost()){
            $brand=request()->post();
            $result=$this->validate($brand,'app\admin\validate\Brand');
            if($result!==true){
                echo json_encode(["status"=>2,"msg"=>$result]);
            }
            $brandModole=new \app\admin\model\Brand();
            if($brandModole->save($brand,['brand_id'=>$brand['brand_id']])){
                $logModel=new Log();
                $log=[
                    "admin_id"=>\think\facade\Session::get("admin")['admin_id'],
                    "admin_ip"=>$_SERVER['REMOTE_ADDR'],
                    "log_content"=>"修改了".$brand['brand_name']."品牌",
                    "log_time"=>time()
                ];
                $logModel->save($log);
                echo json_encode(["status"=>1,"msg"=>"ok"]);
            }else{
                echo json_encode(["status"=>0,"msg"=>"修改数据失败"]);
            }
        }
    }

    public function delete()
    {
        $brand_id=intval(request()->post("brand_id",0));
        if($brand_id==0){
            echo json_encode(["status"=>4,"msg"=>"非法请求"]);
        }
        //删除
        $brandModule=new \app\admin\model\Brand();
        $brand=$brandModule->get($brand_id);
        if($brand->delete()){
            //添加日志
            $logModel=new Log();
            $log=[
                "admin_id"=>\think\facade\Session::get("admin")['admin_id'],
                "admin_ip"=>$_SERVER['REMOTE_ADDR'],
                "log_content"=>"删除".$brand['brand_name']."品牌",
                "log_time"=>time()
            ];
            $logModel->save($log);
            echo json_encode(["status"=>1,"msg"=>"ok"]);
        }else{
            echo json_encode(["status"=>0,"msg"=>"no"]);
        }
    }
}

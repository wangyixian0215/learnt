<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-10-13 0013
 * Time: 19:11
 */
namespace app\admin\controller;


use app\admin\model\Brand;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class Productbrand extends Common{
  public function Productbrand(){
      //查询品牌
      $brand=Brand::brand();
      return view()->assign("brand",$brand);
  }
    public function Addproductbrand(){
        if(request()->isGet()){
            return view();
        }
        if(request()->isPost()){
          $brand=request()->post();
            //验证数据
            $result=$this->validate($brand,'app\admin\validate\Brand');
            if($result!==true){
                $this->error($result);
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
                Brand::addBrand($brand);
                echo json_encode(["status" => 0, "msg" => "ok","path"=>$path]);
            } else {
                echo json_encode(["status" => 4, "msg" => "上传失败"]);
            }
        }

    }

}
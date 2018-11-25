<?php
/**
 * Created by PhpStorm.
 * User: lwen_phper@163.com
 * Date: 2018/8/13
 * Time: 15:00
 *
 * 七牛文件上传接口
 *
 */
namespace Portal\Controller;
use Common\Controller\HomebaseController;
use Portal\Util\Page;
use Qiniu\Auth;
require_once '../simplewind/Core/Library/Vendor/Qiniu/autoload.php';

class QiniuController extends HomebaseController{
    /**
     *
     * APP使用
     *
     */
    public function upload(){
        $accessKey = C("UPLOAD_FILE_QINIU")['driverConfig']['accessKey'];
        $secretKey = C("UPLOAD_FILE_QINIU")['driverConfig']['secretKey'];

        $bucket = C("UPLOAD_FILE_QINIU")['driverConfig']['bucket'];

        $auth = new Auth($accessKey, $secretKey);
        $token = $auth->uploadToken($bucket);

        echo json_encode(['code'=>1,'msg'=>'','token'=>$token]);

    }





}
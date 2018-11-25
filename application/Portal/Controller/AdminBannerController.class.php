<?php
namespace Portal\Controller;

use Common\Controller\AdminbaseController;

class AdminBannerController extends AdminbaseController
{
    //首页banner图
    public function Index()
    {

        if(IS_POST){
            $data= array(
                "img"=>json_encode(I('post.smeta')),
            );
            $res =M("banner")->where(array('name'=>"index"))->save($data);
            if($res) {
                $this->success("修改成功！");
            }else{
                $this->error("修改成功！");
            }
        }

        $row = M("banner")->where(array('name'=>"index"))->getField("img");

        $this->assign("row",json_decode($row));
        $this->display("index");
    }

    //news banner图
    public function news()
    {
        if(IS_POST){
            $data= array(
                "img"=>I('img'),
            );

            $res =M("banner")->where(array('name'=>"news"))->save($data);
            if($res) {
                $this->success("修改成功！");
            }else{
                $this->error("修改成功！");
            }
        }
        $row = M("banner")->where(array('name'=>"news"))->getField("img");

        $this->assign("row",$row);
        $this->display("news");
    }

    //Product banner图
    public function Product()
    {
        if(IS_POST){
            $data= array(
                "img"=>I('img'),
            );
            $res =M("banner")->where(array('name'=>"product"))->save($data);
            if($res) {
                $this->success("修改成功！");
            }else{
                $this->error("修改成功！");
            }
        }
        $row = M("banner")->where(array('name'=>"product"))->getField("img");

        $this->assign("row",$row);
        $this->display("product");

    }

    //about banner图
    public function about()
    {
        if(IS_POST){
            $data= array(
                "img"=>I('img'),
            );

            $res =M("banner")->where(array('name'=>"about"))->save($data);
            if($res) {
                $this->success("修改成功！");
            }else{
                $this->error("修改成功！");
            }
        }
        $row = M("banner")->where(array('name'=>"about"))->getField("img");

        $this->assign("row",$row);
        $this->display("about");
    }
}
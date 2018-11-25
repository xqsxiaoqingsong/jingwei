<?php

namespace Portal\Controller;


use Common\Controller\HomebaseController;

class AboutusController extends HomebaseController
{
    //about首页
    public function Index(){
        $rows=M('xiangmu')->order(array('ctime'=>'desc'))->limit('4')->select();

        foreach($rows as $k=>&$v){
            $v['img']=json_decode($v['smeta'],true);
            $v['img']=$v['img']['thumb'];
        }

        //查询about页面的banner图
        $banner=M('banner')->where(array('name'=>'about'))->getField('img');

        $this->assign('banner',$banner);
        $this->assign('rows',$rows);
        $this->display(":about");
    }



   /* //about详情页
    public function detail(){
        $id=I('get.id');
        $rows=M('xiangmu')->where(array('id'=>$id))->find();
        $smeta = json_decode($rows['smeta'],true);
        $rows['img']=$smeta[0]['url'];
        //print_r($rows);die;
        $this->assign('rows',$rows);
        $this->display(":aboutdetail");
    }*/
}
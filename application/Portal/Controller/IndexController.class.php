<?php
/*
 *      _______ _     _       _     _____ __  __ ______
 *     |__   __| |   (_)     | |   / ____|  \/  |  ____|
 *        | |  | |__  _ _ __ | | _| |    | \  / | |__
 *        | |  | '_ \| | '_ \| |/ / |    | |\/| |  __|
 *        | |  | | | | | | | |   <| |____| |  | | |
 *        |_|  |_| |_|_|_| |_|_|\_\\_____|_|  |_|_|
 */
/*
 *     _________  ___  ___  ___  ________   ___  __    ________  _____ ______   ________
 *    |\___   ___\\  \|\  \|\  \|\   ___  \|\  \|\  \ |\   ____\|\   _ \  _   \|\  _____\
 *    \|___ \  \_\ \  \\\  \ \  \ \  \\ \  \ \  \/  /|\ \  \___|\ \  \\\__\ \  \ \  \__/
 *         \ \  \ \ \   __  \ \  \ \  \\ \  \ \   ___  \ \  \    \ \  \\|__| \  \ \   __\
 *          \ \  \ \ \  \ \  \ \  \ \  \\ \  \ \  \\ \  \ \  \____\ \  \    \ \  \ \  \_|
 *           \ \__\ \ \__\ \__\ \__\ \__\\ \__\ \__\\ \__\ \_______\ \__\    \ \__\ \__\
 *            \|__|  \|__|\|__|\|__|\|__| \|__|\|__| \|__|\|_______|\|__|     \|__|\|__|
 */
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace Portal\Controller;
use Common\Controller\HomebaseController; 
/**
 * 首页
 */
class IndexController extends HomebaseController {
	
    //首页 小夏是老猫除外最帅的男人了
	public function index() {
	    //查找banner图
        $banner=M('banner')->where(array('name'=>'index'))->select();
        foreach($banner as $k=>&$v){
            $v['image']=json_decode($v['img']);
        }

        //查找PROJECT REFERENCES模块信息
        $rows=M('xiangmu')->order(array('ctime'=>'desc'))->limit('4')->select();

        foreach($rows as $k=>&$v){
            $v['img']=json_decode($v['smeta'],true);
            $v['img']=$v['img']['thumb'];
        }
        $this->assign('rows',$rows);
        $this->assign('banner',$banner);
    	$this->display(":index");
    }

    //首页中产品模块
    public function product(){

    }

    //首页PRODUCTS SOLUTIONS模块详情页
    public function detail(){
	    $this->display(":detail");
    }

    //PROJECT REFERENCES详情页
    public function lists(){
        $id=I('get.id');
        $rows=M('xiangmu')->where(array('id'=>$id))->find();

        $rows['smeta']=json_decode($rows['smeta'],true);


        $this->assign('rows',$rows);
        $this->display(":aboutlist");
    }

}



<?php
namespace Portal\Controller;
use Common\Controller\HomebaseController;
use Portal\Util\Page;
class SolutionlistController extends HomebaseController
{

    //产品首页
    function Index(){
        //---------查询分类开始-----------------
        $category=M('terms')->where(array('parent'=>2))->getField('term_id,name');//寻找顶级分类

        $this->assign('category',$category);
        $cid=I('GET.cid',array_keys($category)[0]);

        if($cid) {
            $this->category($cid);
        }

        //---------查询分类结束-----------------

        //--------------查询该分类对应的产品进行分页开始---------------
        $p = I('get.p',1);



        $where = array();

        $list =array();
        if(I('get.c')){
            $ids =M("solution_relationships")->where(array("term_id"=>I('get.c')))->getfield("object_id,term_id");


            if(!empty($ids)) {
                $ids = implode(",", array_keys($ids));

                $where["id"] = array("in", $ids);

            }else{
                $where["id"] = 0;
            }

        }else if(I('get.cid')){
            $cid = I('get.cid');
            $ids = $this->get_allid($cid);

            $ids[] = $cid;
            $ids = implode(",", $ids);

            $ids = M("solution_relationships")->where(array("term_id" => array("in", $ids)))->getfield("object_id,term_id");
            if(!empty($ids)) {
                $ids = implode(",", array_keys($ids));

                $where["id"] = array("in", $ids);
            }else{
                $where["id"] = 0;
            }

        }


        if(I("get.keywords")){
            $where['post_title'] = array("like","%".I("get.keywords")."%");//根据产品名搜索

            //$where['post_title'] = 'FIND_IN_SET(aaa,post_excerpt)';
        }
        $list = M('solution')->where($where)->order('post_modified desc')->page($p . ',8')->select();
        //print_r($list);die;
        foreach($list as $k=>&$v){

            $smeta = json_decode($v['smeta'],true);
            if(!empty($smeta['thumb'])){
                $v['img'] = $smeta['thumb'];
            }else {
                $v['img'] = $smeta["photo"][0]['url'];
            }
        }

        $this->assign('list',$list);// 赋值数据集
        $count =  M('solution')->where($where)->count();// 查询满足要求的总记录数
        $Page = new Page($count,9);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();// 分页显示输出


        //--------------查询该分类对应的产品进行分页结束---------------
        //新闻banenr
        $banner=M('banner')->where(array('name'=>'product'))->getField('img');

        $this->assign('banner',$banner);
        $this->assign('cid',$cid);
        $this->assign("list",$list);
        $this->assign("show",$show);
        $this->display(':solutionlist');
    }


    //产品详情页
    function detail(){
        $id=I('get.id');
        $rows=M('solution')->where(array('id'=>$id))->find();//查产品详情
        $smeta = json_decode($rows['smeta'],true);
        $rows['img']=$smeta["photo"][0]['url'];

        //查找产品详情页Categories栏目下的分类
        $termid=M('solution_relationships')->where(array('object_id'=>$id))->getfield('term_id');

        $category=M('terms')->where(array('term_id'=>$termid))->getfield('parent');
        $res=M('terms')->where(array('parent'=>$category,'term_id'=>array('neq',$termid)))->field('name,term_id')->select();

        //新闻banenr
        $banner=M('banner')->where(array('name'=>'product'))->getField('img');

        $this->assign('banner',$banner);
        $this->assign('res',$res);
        $this->assign('rows',$rows);
        $this->display(":relationship");
    }



    function category($pid){
        $erji=M('terms')->where(array('parent'=>$pid))->select();
        $str="";
        $c=I('get.c');
        foreach($erji as $k=>$v) {
            if($c==$v['term_id']){
                $str .= "<li><span><i rel='".$v['term_id']."' class='ckon'></i></span>".$v['name']."</li>";
            }else{
                $str .= "<li><span><i rel='".$v['term_id']."'></i></span>".$v['name']."</li>";
            }

        }
        if(IS_AJAX) {
            $this->ajaxReturn($str, "EVAL");
        }

        $this->assign("erji",$str);

    }

    //查询某个id对应的所有子分类
    public function get_allid($id,$cate=array()){

        $zi = M('terms')->field("term_id")->where(array('parent'=>$id))->select();
        foreach($zi as $v){
            $cate[] = $v['term_id'];
            $cate = $this->get_allid($v['term_id'],$cate);
        }
        return $cate;

    }




}
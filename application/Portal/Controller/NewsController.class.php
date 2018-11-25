<?php
/**
 * Created by PhpStorm.
 * User: xin
 * Date: 2018/8/16
 * Time: 16:12
 */

namespace Portal\Controller;
use Portal\Util\Page;

use Common\Controller\HomebaseController;

class NewsController extends HomebaseController
{


    protected $news_model ='';
    protected $terms_model ="";
    protected $term_rel_model ="";
    protected $tpl = array(
        "9"=>"companynews",
        "10"=>"blog-classic",
        '11'=>"exhibitionevents"
    );
    protected $detail_tpl = array(
         9=>"newsList",
         10=>"textilenews",
         11=>"newsList"

    );
    function _initialize() {
        parent::_initialize();
        $this->news_model = M('posts');
        $this->terms_model = M('terms');
        $this->term_rel_model = M('term_relationships');
    }


    //新闻列表页
    public function index()
    {
        $p = I('get.p',1);
        $where[post_status] = array("neq",3);
       
			 
		if(I("get.keywords")){
            $where['post_title'] = array("like","%".I("get.keywords")."%");//根据产品名搜索
            $cid  = 9;
            $list = $this->news_model->where($where)->order('post_modified desc')->page($p.',9')->select();
            //$where['post_title'] = 'FIND_IN_SET(aaa,post_excerpt)';
        }else{
			$cid = I('get.cid');
			$list  =array();
			if(!empty($cid)){

				 $news_ids = $this->term_rel_model->where(array('term_id'=>$cid))->getfield("tid,object_id");
				// print_r($news_ids);die;
				 if(!empty($news_ids)){


					 $where['id'] = array('in', implode(",", $news_ids));
					 $list = $this->news_model->where($where)->order('post_modified desc')->page($p.',9')->select();
				 }
			}
        }

        foreach($list as $k=>&$v){

            $smeta = json_decode($v['smeta'],true);

            if(!empty($smeta['thumb'])){
                $v['img'] = $smeta['thumb'];
            }else {
                $v['img'] = $smeta["photo"][0]['url'];
            }
        }

        $where = array();

       

        $this->assign('list',$list);// 赋值数据集
        $count = $this->news_model->where($where)->count();// 查询满足要求的总记录数
        $Page = new Page($count,9);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();// 分页显示输出


        $user =M('users')->getField("id,user_nicename");

        //新闻banenr
        $banner=M('banner')->where(array('name'=>'news'))->getField('img');

        //查询列表页Popular Posts一栏三条新闻
        $cid=I('get.cid');
        $objectid=M('term_relationships')->where(array('term_id'=>10))->limit(3)->getfield('object_id',true);
        $objectid =implode(",",$objectid);

        $res=M('posts')->where(array('id'=>array('in',$objectid)))->select();
        foreach($res as $k=>&$v){
            $v['img']=json_decode($v['smeta'],true);
            $v['img']=$v['img']['thumb'];
        }

        $this->assign('res',$res);
        $this->assign('banner',$banner);
        $this->assign('user',$user);
        $this->assign('list',$list);// 赋值分页输出

        $this->assign('show',$show);

        $tpl =  $this->tpl;
        //echo $tpl[$cid];die;
        $this->display(":".$tpl[$cid]);
    }


    //新闻详情页
    public function news_detail(){
        $id=I('get.id');
        $rows=M('posts')->where(array('id'=>$id,"jw_posts.post_status"=>array("neq",3)))->find();//查posts文章表

        $rows["smeta"] = json_decode($rows["smeta"],true);


        $res=M('terms')->join('jw_term_relationships ON jw_term_relationships.term_id=jw_terms.term_id')->where(array('jw_term_relationships.object_id'=>$id))->field('jw_terms.term_id,jw_terms.name')->find();
        $term_id =$res["term_id"];
        //echo $term_id;die;
        $detail_tpl = $this->detail_tpl;
        if(!empty($term_id) && in_array($term_id,array_keys($detail_tpl))){
            $tpl= $detail_tpl[$term_id];

        }else{
            $tpl = $this->detail_tpl[9];
        }

        $category = $res["name"];

        //新闻banenr
        $banner=M('banner')->where(array('name'=>'news'))->getField('img');

        //根据不同的模板查询不同的数据
       if($tpl=="newsList"){
           //查找新闻分类


           //新闻详情上一页
           $prev=M('posts')->where(array("id"=>array('lt',$id),"jw_posts.post_status"=>array("neq",3)))->field('id')->order(array('id'=>'desc'))->find();


           //新闻详情下一页
           $next=M('posts')->where(array("id"=>array('gt',$id),"jw_posts.post_status"=>array("neq",3)))->field('id')->order(array('id'=>'asc'))->find();

           $this->assign('banner',$banner);
           $this->assign('prev',$prev);
           $this->assign('next',$next);
           $this->assign('rows',$rows);
           $this->assign('category',$category);


       }else if($tpl=="textilenews") {

           $author = M("users")->where(array("id" => $rows['post_author']))->getField("user_nicename");
           //查询标签
           $rows['post_keywords'] = str_replace(' ', ',', $rows['post_keywords']);
           $rows['post_keywords'] = explode(",", $rows['post_keywords']);

           //查询详情页Popular Posts一栏三条新闻
           $cid=I('get.cid');
           $objectid=M('term_relationships')->where(array('term_id'=>10,"object_id"=>array("neq",$id)))->limit(3)->getfield('object_id',true);
           $objectid =implode(",",$objectid);
           $id=I('get.id');
           $res=M('posts')->where(array('id'=>array('in',$objectid)))->select();
           foreach($res as $k=>&$v){
               $v['img']=json_decode($v['smeta'],true);
               $v['img']=$v['img']['thumb'];
           }



           $this->assign('banner',$banner);
           $this->assign('res', $res);
           $this->assign('rows', $rows);
           $this->assign('author', $author);
       }

       //echo $tpl;die;
        $this->display(":".$tpl);
    }

}
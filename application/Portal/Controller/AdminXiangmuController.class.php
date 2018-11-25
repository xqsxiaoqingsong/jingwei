<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Tuolaji <479923197@qq.com>
// +----------------------------------------------------------------------
namespace Portal\Controller;

use Common\Controller\AdminbaseController;

class AdminXiangmuController extends AdminbaseController {
    protected $posts_model;
    protected $term_relationships_model;
    protected $terms_model;

    function _initialize() {
        parent::_initialize();
        $this->posts_model = M('xiangmu');
//        $this->terms_model = D("Portal/Terms");
        //$this->term_relationships_model = D("Portal/TermRelationships");
    }

    // 后台文章管理列表
    public function index(){

        $xiangmu =M("xiangmu")->order(array('ctime'=>'desc'))->select();
        $this->assign("xiangmu",$xiangmu);
        $this->display();
    }

    public function add(){
        $this->display();
    }
    //新增提交
    public function add_post(){
        if (IS_POST) {

           if(!empty($_POST['photos_alt']) && !empty($_POST['photos_url'])){
                foreach ($_POST['photos_url'] as $key=>$url){
                   $photourl=sp_asset_relative_url($url);
                    $_POST['smeta']['photo'][]=array("url"=>$photourl,"alt"=>$_POST['photos_alt'][$key]);
                }
            }
            $_POST['smeta']['thumb'] = sp_asset_relative_url($_POST['smeta']['thumb']);
            if(!empty($_POST['photos_alt']) && !empty($_POST['photos_url'])){
                foreach ($_POST['photos_url'] as $key=>$url){
                    $photourl=sp_asset_relative_url($url);
                    $_POST['post']['smeta'][]=array("url"=>$photourl,"alt"=>$_POST['photos_alt'][$key]);
                }
            }
            $_POST['post']['smeta'] = json_encode($_POST['smeta']);

            $result=M("xiangmu")->add($_POST['post']);

            if ($result) {

                $this->success("添加成功！");
            } else {
                $this->error("添加失败！");
            }

        }
    }

    // 文章编辑
    public function edit(){
        $id=I("get.id");
        $rows=M('xiangmu')->where(array('id'=>$id))->find();
        $rows['smeta']=json_decode($rows['smeta'],true);

        $this->assign('rows',$rows);
        $this->display();
    }

    // 文章编辑提交
    public function edit_post(){
        $id=I('get.id');

        if(IS_POST){

            $_POST['post']['smeta'] = json_encode($_POST['smeta']);


            $res=M('xiangmu')->where(array('id'=>$id))->save($_POST['post']);

            if($res){
                $this->success("修改成功！");
            }else{
                $this->error("修改失败");
            }
        }
    }


    //项目介绍
    public function description(){
        //$res = M("xiangmu_relation")->add(array("id"=>4,"title"=>"fasdfas"),array(),true);
        //$res = M("xiangmu_relation")->add(array("title"=>"fasdfas"),array(),true);

     if(IS_AJAX){
         //处理新增的

         $xiangmu_id = I("post.xiangmu_id", array());

        $post = I("post.post",array());
         $res =array();
         //print_r($post);die;
        foreach($post as $key=>$value){
            $res = M("xiangmu_relation")->add($value,array(),true);

        }
        if($res){
            $this->success("添加成功！");
        }else {
            $this->error("添加失败！");
        }


     }

     //查询已经存在的项目：
        $list =   M("xiangmu_relation")->where(array("xiangmu_id"=>I('get.id')))->select();
     //print_r($list);die;
        $this->assign("list",$list);
        $this->display();
    }



    // 文章删除
    public function delete(){
        if(isset($_GET['id'])){
            $id = I("get.id",0,'intval');

            if ($this->posts_model->where(array('id'=>$id))->delete() !==false) {

                $this->success("删除成功！");
            } else {
                $this->error("删除失败！");
            }
        }

        if(isset($_POST['ids'])){
            $ids = I('post.ids/a');

            if ($this->posts_model->where(array('id'=>array('in',$ids)))->save(array('post_status'=>3))!==false) {
                $this->success("删除成功！");
            } else {
                $this->error("删除失败！");
            }
        }
    }
    public function del_descption(){
        if(isset($_GET['id'])){
            $id = I("get.id",0,'intval');

            if (M("xiangmu_relation")->where(array('id'=>$id))->delete() !==false) {

                $this->success("删除成功！");
            } else {
                $this->error("删除失败！");
            }
        }


    }
    // 文章审核
    public function check(){
        if(isset($_POST['ids']) && $_GET["check"]){
            $ids = I('post.ids/a');

            if ( $this->posts_model->where(array('id'=>array('in',$ids)))->save(array('post_status'=>1)) !== false ) {
                $this->success("审核成功！");
            } else {
                $this->error("审核失败！");
            }
        }
        if(isset($_POST['ids']) && $_GET["uncheck"]){
            $ids = I('post.ids/a');

            if ( $this->posts_model->where(array('id'=>array('in',$ids)))->save(array('post_status'=>0)) !== false) {
                $this->success("取消审核成功！");
            } else {
                $this->error("取消审核失败！");
            }
        }
    }

    // 文章置顶
    public function top(){
        if(isset($_POST['ids']) && $_GET["top"]){
            $ids = I('post.ids/a');

            if ( $this->posts_model->where(array('id'=>array('in',$ids)))->save(array('istop'=>1))!==false) {
                $this->success("置顶成功！");
            } else {
                $this->error("置顶失败！");
            }
        }
        if(isset($_POST['ids']) && $_GET["untop"]){
            $ids = I('post.ids/a');

            if ( $this->posts_model->where(array('id'=>array('in',$ids)))->save(array('istop'=>0))!==false) {
                $this->success("取消置顶成功！");
            } else {
                $this->error("取消置顶失败！");
            }
        }
    }

    // 文章推荐
    public function recommend(){
        if(isset($_POST['ids']) && $_GET["recommend"]){
            $ids = I('post.ids/a');

            if ( $this->posts_model->where(array('id'=>array('in',$ids)))->save(array('recommended'=>1))!==false) {
                $this->success("推荐成功！");
            } else {
                $this->error("推荐失败！");
            }
        }
        if(isset($_POST['ids']) && $_GET["unrecommend"]){
            $ids = I('post.ids/a');

            if ( $this->posts_model->where(array('id'=>array('in',$ids)))->save(array('recommended'=>0))!==false) {
                $this->success("取消推荐成功！");
            } else {
                $this->error("取消推荐失败！");
            }
        }
    }

    // 文章批量移动
    public function move(){
        if(IS_POST){
            if(isset($_GET['ids']) && $_GET['old_term_id'] && isset($_POST['term_id'])){
                $old_term_id=I('get.old_term_id',0,'intval');
                $term_id=I('post.term_id',0,'intval');
                if($old_term_id!=$term_id){
                    $ids=explode(',', I('get.ids/s'));
                    $ids=array_map('intval', $ids);

                    foreach ($ids as $id){
                        $this->term_relationships_model->where(array('object_id'=>$id,'term_id'=>$old_term_id))->delete();
                        $find_relation_count=$this->term_relationships_model->where(array('object_id'=>$id,'term_id'=>$term_id))->count();
                        if($find_relation_count==0){
                            $this->term_relationships_model->add(array('object_id'=>$id,'term_id'=>$term_id));
                        }
                    }

                }

                $this->success("移动成功！");
            }
        }else{
            $tree = new \Tree();
            $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
            $terms = $this->terms_model->order(array("path"=>"ASC"))->select();
            $new_terms=array();
            foreach ($terms as $r) {
                $r['id']=$r['term_id'];
                $r['parentid']=$r['parent'];
                $new_terms[] = $r;
            }
            $tree->init($new_terms);
            $tree_tpl="<option value='\$id'>\$spacer\$name</option>";
            $tree=$tree->get_tree(0,$tree_tpl);

            $this->assign("terms_tree",$tree);
            $this->display();
        }
    }

    // 文章批量复制
    public function copy(){
        if(IS_POST){
            if(isset($_GET['ids']) && isset($_POST['term_id'])){
                $ids=explode(',', I('get.ids/s'));
                $ids=array_map('intval', $ids);
                $uid=sp_get_current_admin_id();
                $term_id=I('post.term_id',0,'intval');
                $term_count=$terms_model=M('Terms')->where(array('term_id'=>$term_id))->count();
                if($term_count==0){
                    $this->error('分类不存在！');
                }

                $data=array();

                foreach ($ids as $id){
                    $find_post=$this->posts_model->field('post_keywords,post_source,post_content,post_title,post_excerpt,smeta')->where(array('id'=>$id))->find();
                    if($find_post){
                        $find_post['post_author']=$uid;
                        $find_post['post_date']=date('Y-m-d H:i:s');
                        $find_post['post_modified']=date('Y-m-d H:i:s');
                        $post_id=$this->posts_model->add($find_post);
                        if($post_id>0){
                            array_push($data, array('object_id'=>$post_id,'term_id'=>$term_id));
                        }
                    }
                }

                if ( $this->term_relationships_model->addAll($data) !== false) {
                    $this->success("复制成功！");
                } else {
                    $this->error("复制失败！");
                }
            }
        }else{
            $tree = new \Tree();
            $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
            $terms = $this->terms_model->order(array("path"=>"ASC"))->select();
            $new_terms=array();
            foreach ($terms as $r) {
                $r['id']=$r['term_id'];
                $r['parentid']=$r['parent'];
                $new_terms[] = $r;
            }
            $tree->init($new_terms);
            $tree_tpl="<option value='\$id'>\$spacer\$name</option>";
            $tree=$tree->get_tree(0,$tree_tpl);

            $this->assign("terms_tree",$tree);
            $this->display();
        }
    }

    // 文章回收站列表
    public function recyclebin(){
        $this->_lists(array('post_status'=>array('eq',3)));
        $this->_getTree();
        $this->display();
    }

    // 清除已经删除的文章
    public function clean(){
        if(isset($_POST['ids'])){
            $ids = I('post.ids/a');
            $ids = array_map('intval', $ids);
            $status=$this->posts_model->where(array("id"=>array('in',$ids),'post_status'=>3))->delete();
            $this->term_relationships_model->where(array('object_id'=>array('in',$ids)))->delete();

            if ($status!==false) {
                $this->success("删除成功！");
            } else {
                $this->error("删除失败！");
            }
        }else{
            if(isset($_GET['id'])){
                $id = I("get.id",0,'intval');
                $status=$this->posts_model->where(array("id"=>$id,'post_status'=>3))->delete();
                $this->term_relationships_model->where(array('object_id'=>$id))->delete();

                if ($status!==false) {
                    $this->success("删除成功！");
                } else {
                    $this->error("删除失败！");
                }
            }
        }
    }

    // 文章还原
    public function restore(){
        if(isset($_GET['id'])){
            $id = I("get.id",0,'intval');
            if ($this->posts_model->where(array("id"=>$id,'post_status'=>3))->save(array("post_status"=>"1"))) {
                $this->success("还原成功！");
            } else {
                $this->error("还原失败！");
            }
        }
    }


}
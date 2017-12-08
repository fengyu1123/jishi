<?php
namespace Smi\Controller;
use Think\Controller;
class FriendsController extends CommonController {
    public function index(){
        // echo CONTROLLER_NAME;die;
        if($_GET['keyword']){
            $where['title|type'] = array('like',"%{$_GET['keyword']}%");
        }
        // $where['module'] = '1';
        $data = D("Home/CircleFrends")->getArticle($where);
        $this->assign('list',$data['list']);
        $this->assign('page',$data['page']);
        $this->display();
    }
    /*审核*/
    public function byStatus(){
        $id = I('id');
        D("Home/CircleFrends")->where("id={$id}")->save(array('status'=>1)
        );
        $this->redirect('index');
    }
    /*取消审核*/
    public function noStatus(){
        $id = I('id');
        D("Home/CircleFrends")->where("id={$id}")->save(array('status'=>0)
        );
        $this->redirect('index');
    }

    // 氢友圈详情
    public function detail_circle(){
        $id = I('id');
        if($id){
            // M('')
            $res = D('Home/CircleFrends')->getOneArticle($id);
            $this->assign('res',$res);
        }
        // dump($res['newcomment']);
        $this->display();
    }
    /**/
    public function del_circle(){
        $id = I('id');
        if($id){
            D("Home/CircleFrends")->delete($id);
            $this->redirect("index",array('p'=>$_GET['p']),0,'');
        }else{
            $this->error("获取id失败，请重试");
        }

    }

    /*氢活动*/
    public function activity(){
        /*判断条件是否有值*/
        if($_GET['keyword']){
            $where['title|introduction'] = array('like',"%{$_GET['keyword']}%"); 
        }
        $data = D('Activity')->PageList($where);
        $this->assign("page",$data['page']);
        $this->assign("list",$data['list']);
        $this->display();
    }
    /*活动详情*/
    public function activity_detail(){
        $id = I('id');
        if(IS_POST){
            $data = array(
                'title'=>I('title'),
                /*转义存储*/
                'content'=>$_POST('content'),
                'introduction'=>I('introduction'),
                'displayorder'=>I('displayorder'),
                'createtime'=>time()
                );
            $img = $this->upload();
            if($img){
                $data['img'] = $img;
            }
            D("Activity")->where("id = {$id}")->save($data);
            $this->redirect("activity");
        }
        if($id >0 ){
            $res = D('Activity')->where("id={$id}")->find();

        }
        $this->assign("res",$res);
        $this->display('add_activity');
    }
    /*添加活动*/
    public function add_activity(){
        if(IS_POST){
            $img = $this->upload();
            $data = array(
                'title'=>I('title'),
                /*转义存储*/
                'content'=>$_POST['content'],
                'introduction'=>I('introduction'),
                'displayorder'=>I('displayorder'),
                'createtime'=>time(),
                'img'=>$img
                );
            $res = D('Activity')->add($data);
            if($res){
                $this->redirect("activity");
            }
        }
        $this->display();
    }

    /*删除活动*/
    public function del_activity(){
        $id = I('id');
        if($id){
            D('Activity')->delete($id);
            $this->redirect("Friends/activity",array('p'=>$_GET['p']),0,'');
        }else{
            $this->error("获取id失败，请重试");
        }
    }
    /*删除敏感词*/
    public function del_sensitive(){
        $id = I('id');
        if($id){
            D('sensitive_words')->delete($id);
            $this->redirect("Friends/sensitive",array('p'=>$_GET['p']),0,'');
        }else{
            $this->error("获取id失败，请重试");
        }
    }
    /*敏感词*/
    public function sensitive(){
        if(IS_POST){
            $data = array(
                'name'=>I('name'),
                'createtime'=>time()
                ); 
            if(I('op') == 'add'){
                M('sensitive_words')->add($data);
            }elseif(I('op') == 'update'){
                M('sensitive_words')->where('id='.I('id'))->save($data);
            }
            $this->redirect('sensitive');
        }
        $count =M('sensitive_words')->where($where)->count();
        $Page  = new \Think\Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list = M('sensitive_words')->where($where)->order("id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }
    /*图片上传方法*/
    public function upload(){
        if ($_FILES['img']['error'] != 4) {
            $upload = new \Think\Upload();// 实例化上传类
            $upload -> maxSize = 3145728 ;// 设置附件上传大小
            // 设置附件上传类型
            $upload -> exts = array('jpg', 'gif', 'png', 'jpeg');
            $upload -> rootPath = './Uploads/'; // 设置附件上传根目录 
            $upload -> savePath = 'activity/'; // 设置附件上传目录
            $upload -> autoSub = ture; //不要子文件夹
            // 上传文件
            $info = $upload -> upload();
            $img = $info['img']["savepath"].$info['img']["savename"];
            return $img;
        }   
    }

}
?>
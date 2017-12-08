<?php
namespace Smi\Controller;
use Think\Controller;
class BannerController extends CommonController {
		//轮播列表
		public function bannerList(){
			 $banner = M('Banner')->select();
			 $this->assign('banner',$banner);
	         $this->display();
		}

		//添加banner 图
		public function add_banner(){
			 if(!empty($_POST)){
			 	//收集表单数据
	               $data = D('Banner')->create();
	               $upload = new \Think\Upload();// 实例化上传类
						  $upload->maxSize   =     3145728 ;// 设置附件上传大小
						  $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
						  $upload->rootPath  =      './Uploads/stores_img/'; // 设置附件上传根目录
						  $upload->savePath  =      ''; // 设置附件上传（子）目录
							// 上传文件 
						  $info=$upload->upload();
							if(!$info) {// 上传错误提示错误信息
							    $this->error($upload->getError());
							}else{// 上传成功 获取上传文件信息
							    foreach($info as $file){
							        $data['img'] = $upload->rootPath.$file['savepath'].$file['savename'];  
							    }
							    //添加至数据库
							    $data['createtime'] = time();
							    $z = M('Banner')->add($data);
							    if($z){
							    	$this->success('添加成功',U('Banner/bannerList'));
							    }else{
							    	$this->error('添加失败'); 
							    }
							}

			 }else{
			 	$this->display();
			 }
		}

   //删除 bannre
        public function banner_del(){
        	  $id = I('id');
        	  if(!$id){
        	  	 $this->error('参数出错');
        	  	 die;
        	  }
        	  $z = M('Banner')->where(array('id'=>$id))->delete();
        	  if($z){
        	  	  $this->success('删除成功');
        	  }else{
        	  	  $this->error('删除失败');
        	  }
        }

     //修改 Banner 图
     public function banner_upd(){
     	 $this->display();
     }


}
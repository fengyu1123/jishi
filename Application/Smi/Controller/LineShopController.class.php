<?php
namespace Smi\Controller;
use Think\Controller;
class LineShopController extends CommnoController {

    //线下门店展现
	    public function storeList(){
	    	//联合查询  获取门店以及管理员相关信息
	    	 $stores =M('stores')->alias('a')->join('LEFT JOIN qing_member as b ON a.member_uid = b.uid')
                                             ->field('a.*,b.uid,b.openid,b.wx_nickname,b.wx_avatar')
                                             ->order('a.createtime')
	    	                                 ->select();
	    	// dump($sotres);
	    	 $this->assign('stores',$stores);
             $this->display();
	    }

		//添加直营店或体验中心
		public function add_store(){

			 if(!empty($_POST)){//收集表单数据
			 	 $data = D('stores')->create();
			 	 $data['password'] = md5($data['password']);
			 	 $data['createtime'] = time();
			 	 $z = D('stores')->add($data);
			 	 if($z){
			 	 	//插入成功后，添加上传的现场图片
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
						        $map['img'] = $upload->rootPath.$file['savepath'].$file['savename'];
						        $map['createtime'] = time();
						        $map['storeid'] = $z;
						        M('stores_img')->add($map);
						    }
						}
			 	 	 $this->success('添加成功',U('LineShop/storeList'));
			 	 }else{
			 	 	 $this->error('添加失败请重新添加');
			 	 }	  
			 }else{
			 	//调用member
			 	$member= $this->member();
			 	$this->assign('member',$member);
			 	$this->display();
			 }
		  }

        //添加直营店现场图
        protected function stores_imgs($id,$imgs){
             
        }
		//查找管理员
		protected function member(){
			  $member = M('Member')->select();
			  return($member);
		}

		//启用或禁用体验店
	    public function off(){
          $id = I('id');
          $status = I('status');
          if($status == 0){
          	 $data['status'] = 1;
           }
          if($status == 1){
          	  $data['status'] = 0;
          }

          $z = M('stores')->where(array('id'=>$id))->save($data);
          if($z){
          	$this->ajaxReturn(1,json);
          }else{
          	$this->ajaxReturn(2,json);
          }
          
	   }
		
}
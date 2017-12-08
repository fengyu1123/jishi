<?php
namespace Smi\Controller;
use Think\Controller;
/**
* 商城管理。
*/
class ShopController extends Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	//*******************************************************************************************************************
	//				商品列表
	//*******************************************************************************************************************

	// 商品列表
	public function shoplist()
	{
		$where['status'] = array('egt',0);
		$count      = M('yu_goodsprize')->where($where)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		$data = M('yu_goodsprize')->where($where)->order('displayorder desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$len = count($data);
		for ($i=0; $i < $len; $i++) { 
			$data[$i]['status'] == 1 ? $data[$i]['status'] = '上架' : $data[$i]['status'] = '下架';
			$data[$i]['attr'] == 1 ? $data[$i]['attr'] = '新品' : $data[$i]['attr'] = '特价';
		}
		$this->assign('data',$data);
		$this->assign('page',$show);
		$this->display();
	}

	public function addgoods()
	{
		if(IS_POST){
			$len = count($_FILES['carousel']['name']);
			for ($i=0; $i < $len; $i++) { 
				$_FILES['carousel'.$i]['name'] = $_FILES['carousel']['name'][$i];
				$_FILES['carousel'.$i]['type'] = $_FILES['carousel']['type'][$i];
				$_FILES['carousel'.$i]['tmp_name'] = $_FILES['carousel']['tmp_name'][$i];
				$_FILES['carousel'.$i]['error'] = $_FILES['carousel']['error'][$i];
				$_FILES['carousel'.$i]['size'] = $_FILES['carousel']['size'][$i];
			}
			unset($_FILES['carousel']);
			$upload = new \Think\Upload();// 实例化上传类
		    $upload->maxSize   =     3145728 ;// 设置附件上传大小
		    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		    $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
		    $upload->savePath  =     'goods/'; // 设置附件上传（子）目录
		    if ($_FILES['prizeimg']['error'] < 1 && $_FILES['prizeimg'] != null) {
				// 上传单个文件 
			    $info   =   $upload->uploadOne($_FILES['prizeimg']);
			    if(!$info) {// 上传错误提示错误信息
			        $this->error($upload->getError());
			    }else{// 上传成功
			    	$prizeimg = $info['savepath'].$info['savename'];
			    }
			}
			//循环轮播图
			for ($i=0; $i < $len; $i++) { 
				if($_FILES['carousel'.$i]['error'] < 1 && $_FILES['carousel'.$i] != null){
					// 上传文件 
					$info   =   $upload->uploadOne($_FILES['carousel'.$i]);
					if(!$info) {// 上传错误提示错误信息
					    $this->error($upload->getError());
					}else{// 上传成功 获取上传文件信息
					    $img[] =  $info['savepath'].$info['savename'];
					}
				}
			}
			$data = array(
					'goodsname'=>$_POST['goodsname'],
					'status'=>$_POST['status'],
					'attr'=>$_POST['attr'],
					'displayorder'=>$_POST['displayorder'],
					'surplusnum'=>$_POST['surplusnum'],
					'attr'=>$_POST['attr'],
					'price'=>$_POST['price'],
					'num'=>$_POST['num'],
					'prizeimg'=>$prizeimg,
					'carousel'=>implode('*', $img),
					'content'=>$_POST['content'],
					'ctime'=>time(),
				);
			$res = M('yu_goodsprize')->add($data);
			if($res){
				$this->success('success',U('shoplist'),1);
			}else{
				$this->error('添加失败，请重试');
			}
		}else{
			// dump($typedata);die;
			$this->display();
		}
		
	}

	// 编辑
	public function edit()
	{
		// 查询商品信息
		$id = I('id');
		if(!$id){
			$this->error('系统错误，参数丢失');
		}

		if(IS_POST){
			$where['id'] = $id;
			$len = count($_FILES['carousel']['name']);
			for ($i=0; $i < $len; $i++) { 
				$_FILES['carousel'.$i]['name'] = $_FILES['carousel']['name'][$i];
				$_FILES['carousel'.$i]['type'] = $_FILES['carousel']['type'][$i];
				$_FILES['carousel'.$i]['tmp_name'] = $_FILES['carousel']['tmp_name'][$i];
				$_FILES['carousel'.$i]['error'] = $_FILES['carousel']['error'][$i];
				$_FILES['carousel'.$i]['size'] = $_FILES['carousel']['size'][$i];
			}
			unset($_FILES['carousel']);
			$upload = new \Think\Upload();// 实例化上传类
		    $upload->maxSize   =     3145728 ;// 设置附件上传大小
		    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		    $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
		    $upload->savePath  =     'goods/'; // 设置附件上传（子）目录
		    if ($_FILES['prizeimg']['error'] < 1 && $_FILES['prizeimg'] != null) {
				// 上传单个文件 
			    $info   =   $upload->uploadOne($_FILES['prizeimg']);
			    if(!$info) {// 上传错误提示错误信息
			        $this->error($upload->getError());
			    }else{// 上传成功
			    	$prizeimg = $info['savepath'].$info['savename'];
			    }
			}

			for ($i=0; $i < $len; $i++) { 
				if($_FILES['carousel'.$i]['error'] < 1 && $_FILES['carousel'.$i] != null){
					// 上传文件 
					$info   =   $upload->uploadOne($_FILES['carousel'.$i]);
					if(!$info) {// 上传错误提示错误信息
					    $this->error($upload->getError());
					}else{// 上传成功 获取上传文件信息
					    $img[] =  $info['savepath'].$info['savename'];
					}
				}
			}
			$data = array(
					'goodsname'=>$_POST['goodsname'],
					'status'=>$_POST['status'],
					'attr'=>$_POST['attr'],
					'displayorder'=>$_POST['displayorder'],
					'surplusnum'=>$_POST['surplusnum'],
					'attr'=>$_POST['attr'],
					'price'=>$_POST['price'],
					'num'=>$_POST['num'],
					'prizeimg'=>$prizeimg,
					'carousel'=>implode('*', $img),
					'content'=>$_POST['content'],
					'ctime'=>time(),
				);
			$res = M('yu_goodsprize')->where($where)->save($data);
			if($res || $res == 0){
				// 删除旧封面图片
				if($data['thumb']){
					@unlink('Uploads/'.$findres['thumb']);
				}
				// 删除旧轮播图
				if($data['carousel']){
					$imgarr[] = explode('*', $data['carousel']);
					$l = count($imgarr);
					for ($i=0; $i < $l; $i++) { 
						@unlink('Uploads/'.$imgarr[$i]);
					}
				}
				$this->success('success',U('shoplist'),1);
			}else{
				$this->error('编辑修改失败，请重试');
			}
		}else{
			$where['id'] = $id;
			$data = M('yu_goodsprize')->where($where)->find();
			$data['attributes'] = unserialize($data['attributes']);
			$data['content'] = unserialize($data['content']);
			$this->assign('data',$data);
			
			$this->display('addgoods');
		}
	}



	// 删除商品good
	public function deletegood(){
		$id = I('id');
		if(!$id){
			$this->error('系统错误，参数丢失');
		}
		$where['id'] = $id;
		$data['status'] = -6;
		$res = M('yu_goodsprize')->where($where)->save($data);
		if($res){
			$this->success('已删除');
		}else{
			$this->error('删除失败');
		}
	}


}

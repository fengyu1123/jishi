<?php
namespace Smi\Controller;
use Think\Controller;
/**
* 
*/
class CategoryController extends CommonController
{
	
	function __construct()
	{
		parent::__construct();
	}

	//*******************************************************************************************************************
	//												商品分类管理
	//*******************************************************************************************************************

	// 商品分类管理
	public function shoptype()
	{
		$where['parentid'] = 0;
		$count      = M('shop_category')->where($where)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		$data = M('shop_category')->where($where)->order('displayorder desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$len = count($data);
		for ($i=0; $i < $len; $i++) { 
			if($data[$i]['enabled'] == 1){
				$data[$i]['enabled'] = '是';
			}else{
				$data[$i]['enabled'] = '否';
			}

			if($data[$i]['isrecommand'] == 1){
				$data[$i]['isrecommand'] = '是';
			}else{
				$data[$i]['isrecommand'] = '否';
			}
			$Twhere['parentid'] = $data[$i]['id'];
			$data[$i]['Tdata'] = M('shop_category')->where($Twhere)->order('displayorder desc')->select();

		}
		// dump($data);die;
		$this->assign('data',$data);
		$this->assign('page',$show);
		$this->display();
	}

	// 商品分类添加修改
	public function addtype()
	{
		$this->savetype(false);
	}

	// 编辑分类详情
	public function edit_type(){
		$this->savetype(true);
	}
	// 获取上级分类
	public function ajaxlevel(){
		$level = I('level');
		if(!$level){
			$this->ajaxReturn(-1,'json');
		}
		$dd = intval($level) - 1;
		if($dd < 1){
			$this->ajaxReturn(-3,'json');
		}
		// else{
		// 	$this->ajaxReturn('level2','json')
		// }
		$where['level'] = $dd;
		$data = M('shop_category')->where($where)->select();
		if($data){
			$this->ajaxReturn($data,'json');
		}else{
			$this->ajaxReturn(-2,'json');
		}
	}
	// 删除分类
	public function deletetype(){
		$id = I('id');
		if(!$id){
			$this->error('系统错误，参数丢失');
		}
		$where['id'] = $id;
		$res = M('shop_category')->where($where)->delete();
		if($res){
			$this->success('已删除');
		}else{
			$this->error('删除失败');
		}
	}


	// 保存分类表单
	public function savetype($isGetLevel){
		if(IS_POST){
			if ($_FILES['thumb']['error'] < 1 && $_FILES['thumb'] != null) {
				$upload = new \Think\Upload();// 实例化上传类
			    $upload->maxSize   =     3145728 ;// 设置附件上传大小
			    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			    $upload->rootPath  =     './Uploads/shoptype/'; // 设置附件上传根目录
			    $upload->savePath  =     ''; // 设置附件上传（子）目录
			    // 上传文件 
			    $info   =   $upload->uploadOne($_FILES['thumb']);
			    if(!$info) {// 上传错误提示错误信息
			        $this->error($upload->getError());
			    }else{// 上传成功
			    	$data['thumb'] = 'shoptype/'.$info['savepath'].$info['savename'];;
			    }
			}

			$data['name'] = I('name');
			$data['level'] = intval(I('level'))?:1;
			$data['parentid'] = I('parentid');
			$data['enabled'] = I('enabled');
			$data['isrecommand'] = I('isrecommand');
			$data['displayorder'] = I('displayorder');
			$data['link'] = I('link');
			$data['createtime'] = time();
			$id = I('id');
			// dump($data);die;
			if($id){
				// 修改
				$where['id'] = $id;
				$findres = M('shop_category')->where($where)->find();
				$res = M('shop_category')->where($where)->save($data);
				if($res){
					// 修改成功 删除原先图片文件
					if($findres['thumb']){
						@unlink('./Uploads/'.$findres['thumb']);
					}
					$this->success('修改成功',U('shoptype'),1);
				}else{
					$this->error('修改失败');
				}
			}else{
				// 添加
				$res = M('shop_category')->add($data);
				if($res){
					$this->success('添加成功',U('shoptype'),1);
				}else{
					$this->error('添加失败');
				}
			}
		}else{
			// 查找id
			$id = I('id');
			if($id){
				$where['id'] = $id;
				$data = M('shop_category')->where($where)->find();
				// 查找该id是否包含parentid
				if ($data['parentid'] !== 0 && $isGetLevel) {
					$Pwhere['id'] = $data['parentid'];
					$data['Pdata'] = M('shop_category')->where($Pwhere)->find();
				}
				// dump($data);die;
				$this->assign('data',$data);
			}
			$this->display();
		}
	}

}
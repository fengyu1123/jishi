<?php
namespace Smi\Controller;
use Think\Controller;
/**
* 
*/
class SelfhelpController extends CommnoController
{
	
	function __construct()
	{
		parent::__construct();
	}

	//*******************************************************************************************************************
	//												常见问题管理
	//*******************************************************************************************************************

	// 客服服务
	public function index()
	{
		if($_GET['keyword']){
			$where['title'] = array("like","%{$_GET['keyword']}%");
		}else{
			$where = 1;
		}
		$count      = M('Self_help')->where($where)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		$data = M('Self_help')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('data',$data);
		$this->assign('page',$show);
		$this->display();
	}
	public function addquestion(){
		$id = I('id');
		if (IS_POST) {
			$data['title'] = I('title');
			$data['content'] = $_POST['content'];
			$data['type'] = I('type');
			$data['createtime'] = time();						
			//var_dump($id);	
			if($id){
				$where['id'] = $id;				
				$findres = M('Self_help')->where($where)->find();
				$res = M('Self_help')->where($where)->save($data);
				if($res){
					// 修改成功 删除原先图片文件
					if($findres['thumb']){
						@unlink('./Uploads/'.$findres['thumb']);
					}				
					$this->success('修改成功',U('index'),1);
				}else{
					$this->error('修改失败');
				}				
			}else{
				// 添加
				$res = M('Self_help')->add($data);
				if($res){
					$this->success('添加成功',U('index'),1);
				}else{
					$this->error('添加失败');
				}
			}
		}else{
				if($id){
					$where['id']=$id;
					//var_dump($id);
					$data=M('Self_help')->where($where)->find();
					$this->data=$data;		
				}				
				$this->display();
		}		
	}	

	public function deletequestion(){
		$id = I('id');
		if(!$id){
			$this->error('系统错误，参数丢失');
		}
		$where['id'] = $id;
		$res = M('Self_help')->where($where)->delete();
		if($res){
			$this->success('已删除');
		}else{
			$this->error('删除失败');
		}

	}	
	}
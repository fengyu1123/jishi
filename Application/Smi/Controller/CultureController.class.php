<?php
namespace Smi\Controller;
use Think\Controller;
/**
* 
*/
class CultureController extends CommnoController
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function index(){
		
		$data =  M('Carture')->select();
		$this->data=$data;
		$this->display();
		
	}
	public function addIntroduce(){
		$id = I('id');
		if (IS_POST) {
			$data['catitle'] = I('catitle');
			$data['cacontent'] = I('cacontent');
			$data['creatime'] = time();			
			
			//var_dump($id);	
			if($id){
				$where['id'] = $id;							
				$res = M('Carture')->where($where)->save($data);
				if($res){
					// 修改成功 删除原先图片文件				
					$this->success('修改成功',U('index'),1);
				}else{
					$this->error('修改失败');
				}				
			}else{
				// 添加
				$res = M('Carture')->add($data);
				if($res){
					$this->success('添加成功',U('index'),1);
				}else{
					$this->error('添加失败');
				}
			}
		}else{
				//$id=I('id');
				if($id){
					//$where['id']=$id;
					var_dump($id);
					$data=M('Carture')->where($where)->find();
					$this->data=$data;		
				}
				
				$this->display();
		}
		

	}
	public function deleteIntroduce(){
		$id = I('id');
		if(!$id){
			$this->error('系统错误，参数丢失');
		}
		$where['id'] = $id;
		$res = M('Carture')->where($where)->delete();
		if($res){
			$this->success('已删除');
		}else{
			$this->error('删除失败');
		}
	}

	public function Exshare(){
		//anli 
	}

	}
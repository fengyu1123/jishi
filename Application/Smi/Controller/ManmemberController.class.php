<?php
namespace Smi\Controller;
use Think\Controller;
/**
* 会员管理
*/
class ManmemberController extends Controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function showlist(){
		if($_GET['keyword']){
			$keyword=$_GET['keyword'];			
			$result=M('Member');
			$where['qing_member.wx_nickname|qing_member.realname|qing_member.mobile'] = array(
					'like',"%{$keyword}%"
				) ;				
		}else{
			$where=1;
		}
		$count=M('Member')->where($where)->count();
		$Page       = new \Think\Page($count,10);
		$show=$Page->show();
		$data=M('Member')->order('createtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();		
		$this->assign('data',$data);
		$this->assign('page',$show);
		$this->display();
	}
}
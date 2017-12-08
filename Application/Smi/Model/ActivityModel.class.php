<?php

namespace Smi\Model;
use Think\Model;
header("Content-type:text/html;charset=utf-8");
class ActivityModel extends Model{
	/*基本资料保存*/
	public function saveUserinfo($data,$openid){
		if($openid){
			/*保存信息*/
			$this->where("openid='{$openid}'")->save($data);
			return true;
		}
	}
	/*基本资料保存*/
	public function PageList($where = 1){
		$count =$this->where($where)->count();
		$Page  = new \Think\Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		$list = $this->where($where)->order("displayorder desc,id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
		$data = array(
			'page'=>$show,
			'list'=>$list
			);
			return $data;
	}

	/*获取用户信息*/
	public function getUserinfo($openid){
		$res = $this->where("openid='{$openid}'")->find();
		return $res;
	}

	/*达人加1*/
	public function saveMaster($openid,$num=1,$type='master'){
		$this->where("openid = '{$openid}'")->setInc($type,$num);
		return true;
	}

	public function getUid($uid){
		$res = $this->where("uid={$uid}")->find();
		return $res;
	} 
	
}
?>
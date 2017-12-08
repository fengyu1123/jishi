<?php

namespace Smi\Controller;
use Think\Controller;
/**
* 登录
*/
header("Content-Type: text/html; charset=utf-8");
class LoginController extends Controller
{
	
	function __construct()
	{
		parent::__construct();
	}


	public function index()
	{
		$this->display();
	}


	public function dologin()
	{
		$name = I('name');
		$password = I('password');
		$password = md5($password);
		$user = M('admin')->where("name='%s'",array($name))->find();
		
		if($user){
		    if($password==$user['password']){
		        session('useramdin',$user['name']);
		        session('admin_id',$user['id']);
		        $this->redirect('Index/index','',0.1,"<script>alert('登陆成功!');</script>");
		        
		        
		    }else{
		        $this->redirect('Login/index','',0.1,"<script>alert('用户密码输入错误!');</script>");
		    }
		}else{  
			// $data['name'] = $name;
			// $data['password'] = $password;
			// $data['status'] = 1;
			// $data['auth'] = 1;
			// $data['createtime'] = time();
			// $User = M('admin')->data($data)->add();
			// dump($User);die;
		    $this->redirect('Login/index','',0.1,"<script>alert('用户账号输入错误!');</script>");
		}
	}

	public function loginout(){
        session('useramdin',null);
        session('admin_id',null);
        $this->success('退出成功','index',3);
    }








}
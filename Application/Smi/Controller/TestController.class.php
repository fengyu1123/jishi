<?php
namespace Smi\Controller;
use Think\Controller;
class TestController extends CommonController {
    public function index(){
        $this->display();
    }
   
    public function test(){
    	$Auth = new \Think\Auth();
	    //需要验证的规则列表,支持逗号分隔的权限规则或索引数组
	    $name = MODULE_NAME . '/' . ACTION_NAME;
	    //当前用户id
	    $uid = '1';
	    //分类
	    $type = MODULE_NAME;
	    //执行check的模式
	    $mode = 'url';
	    //'or' 表示满足任一条规则即通过验证;
	    //'and'则表示需满足所有规则才能通过验证
	    $relation = 'and';
	    if ($Auth->check($name, $uid, $type, $mode, $relation)) {
	     die('认证：成功');
	    } else {
	     die('认证：失败');
	    }
    }
}
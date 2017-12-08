<?php
namespace Smi\Controller;
use Think\Controller;
header("Content-Type: text/html; charset=utf-8");
class SystemController extends Controller {
    public function index(){
    	/**/
        $admin = M('admin'); // 实例化admin对象
        $count      = $admin->join('qing_auth_group ON qing_admin.auth = qing_auth_group.id')->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $admin->field("qing_admin.id,qing_admin.name,qing_auth_group.title")->join('qing_auth_group ON qing_admin.auth = qing_auth_group.id')->order('qing_admin.id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /*管理员组*/
    public function group(){
        $auth_group = M('auth_group'); // 实例化auth_group对象
        $count      = $auth_group->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $auth_group->order('id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->display();
    }
    /*管理员组修改*/
    public function group_add(){
        if(IS_POST){
            $rules = $_POST['rules'];
            $rules = implode(',',$rules);
            $data = array(
                'title'=>I('title'),
                'status'=>I('status'),
                'rules'=>$rules
                );
            $res = M('auth_group')->add($data);
            if($res){
                $this->redirect('group');
            }
        }
        $rules = M("auth_rule")->select();
        $this->assign("rules",$rules);
        $this->display();
    }
    /*管理员组*/
    public function group_update(){
        $id=I('id');
        if(IS_POST){
            $rules = $_POST['rules'];
            $rules = implode(',',$rules);
            $data = array(
                'title'=>I('title'),
                'status'=>I('status'),
                'rules'=>$rules
                );
            $result = M('auth_group')->where("id={$id}")->save($data);
            $this->redirect("group");
        }
        $res = M('auth_group')->where("id={$id}")->find();
        $res['rules'] = explode(',', $res['rules']);
        $this->assign("res",$res);
        $rules = M("auth_rule")->select();
        $this->assign("rules",$rules);
        $this->display('group_add');
    }
    public function group_del(){
        $id = I('id');
        $res = M('auth_group')->where("id=".$id)->delete();
        if($res){
            $this->redirect("group");
        }
    }
    public function auth(){
    	$auth_rule = M('auth_rule'); // 实例化auth_rule对象
		$count      = $auth_rule->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $auth_rule->order('id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
    	$this->display();
    }
   
    /*规则添加*/
    public function rule_add(){
    	if(IS_POST){
    		$data = array(
    			'name'=>I('name'),
    			'title'=>I('title'),
    			'type'=>I('type'),
    			'status'=>I('status')
    			);
    		M('auth_rule')->add($data);
    		// echo M('auth_rule')->getlastsql();die;
    		$this->redirect("auth");die;
    	}
    	$this->display();
    }
    /*修改规则*/
    public function rule_update(){

    	$id = I('id');
        if(IS_POST){
            $data = array(
                'name'=>I('name'),
                'title'=>I('title'),
                'type'=>I('type'),
                'status'=>I('status')
                );
            M('auth_rule')->where("id={$id}")->save($data);
            $this->redirect("auth");
        }
    	$res = M('auth_rule')->where("id={$id}")->find();
    	$this->assign('res',$res);
    	$this->display('rule_add');
    }
    /*删除规则*/
    public function del_rule(){
    	$id = I('id');
    	M('auth_rule')->where("id=".$id)->delete();
    	$this->redirect('auth');
    }
    /*管理用户添加*/
    public function admin_add(){
        /*随机排序*/
        if(IS_POST){
            $data = array(
                'name'=>I('username'),
                'password'=>md5(trim($_POST['password'])),
                'auth'=>I('auth'),
                'createtime'=>time()
                );
            if(!$data['name']||!$data['password']){
                $this->error('添加失败，请检查填写的参数');die;
            }
            $res = M('admin')->where("name='{$data['name']}'")->find();
            if($res){
                $this->error("账号已存在，不能重复添加");die;
            }
            $res = M('admin')->add($data);
            M('auth_group_access')->add(array(
                'uid'=>$res,
                'group_id'=>$data['auth']
                ));
            $this->redirect('index');
        }
        $group = M('auth_group')->order("rand()")->select();
        $this->assign("group",$group);
        $this->display('admin_add');
    }
    public function admin_update(){
        if(IS_POST){
            $data = array(
                'name'=>I('username'),
                'auth'=>I('auth'),
                );
            if($_POST['password']){
                $data['password']= md5(trim($_POST['password']));
            }
            M('admin')->where("id={$_POST['id']}")->save($data);
            $this->redirect('index');
        }
        $id = I('id');
        $res = M('admin')->where("id={$id}")->find();
        $this->assign('res',$res);
        $group = M('auth_group')->order("rand()")->select();
        $this->assign("group",$group);
        $this->display('admin_add');
    }
    public function admin_del(){
        $id = I('id');
        M('admin')->delete($id);
        M('auth_group_access')->where("uid={$id}")->delete();
        $this->redirect('index');
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
	    $relation = '';
	    if ($Auth->check($name, $uid, $type, $mode, $relation)) {
	     	die('认证：成功');
	    } else {
	    	die('认证：失败');
	    }
    }
}
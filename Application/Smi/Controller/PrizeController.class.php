<?php
namespace Smi\Controller;
use Think\Controller;
class PrizeController extends Controller {
    public function index(){
        $where['status'] = empty($_GET['sta'])?0:$_GET['sta'];
        if($_GET['keyword']){
            $where['phone|mobile'] = empty($_GET['keyword'])?'':$_GET['keyword'];
        }
        $count=M('yu_prize')->where($where)->count();
        $Page       = new \Think\Page($count,10);
        $show=$Page->show();
        $list=M('yu_prize')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();  
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }

    public function detail(){
        $where['id'] = $_GET['id'];
        $res = M('yu_prize')->where($where)->find();
        $this->assign('res',$res);
        $this->display();
    }

    public function setStatus(){
        $id = $_GET['id'];
        M('yu_prize')->where(array('id'=>$id))->setField('status',2);
        $this->success('发送成功');
    }

    public function setLogistics(){
        $id = $_POST['id'];
        $logisticsSN = $_POST['logisticsSN'];
        M('yu_prize')->where(array('id'=>$id))->setField('logisticsSN',$logisticsSN);
        $this->ajaxReturn(1,'JSON');
    }
   
}
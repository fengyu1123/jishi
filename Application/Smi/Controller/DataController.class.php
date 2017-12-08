<?php
namespace Smi\Controller;
use Think\Controller;
class DataController extends Controller {
    public function member_grow(){
        $result = $this->getData();

        $this->assign('data',$result);

        $this->display();
    }

    public function member_follow(){
        $result = $this->getData('follow');

        $this->assign('data',$result);

        $this->display();
    }

    public function spiltArr($arr,$attr){
        $i = 0;
        $result = array();
        foreach ($arr as $key => $val) {
            foreach ($attr as $k => $v) {
                $result[$v][$i] = $key;
                $result[$v][$i] = $val;
            }
            $i++;
        }
        return $result;
    }

    public function getData($arg = 'createtime'){
        $data = M('member')->select();
        // 年月日对应的人数
        /**
         * [$key description]
         * @var count1 -> 年
         * @var count2 -> 月
         * @var count3 -> 日
         */
        foreach ($data as $key => $val) {
            if($val[$arg]){
                $count1[date('Y',$val['createtime'])] += 1;
                $count2[date('Y-m',$val['createtime'])] += 1;
                $count3[date('Y-m-d',$val['createtime'])] += 1;
            }
        }

        $result1 = $this->spiltArr($count1,array('time','total'));
        $result2 = $this->spiltArr($count2,array('time','total'));
        $result3 = $this->spiltArr($count3,array('time','total'));

        $result = array('year'=>$result1,'month'=>$result2,'day'=>$result3);

        $result = json_encode($result);
        return $result;
    }
   
}
<?php
namespace Smi\Controller;
use Think\Controller;
class CityController extends CommonController {

	//城市列表
    public function citylist(){
        $this->display();
    }

    //添加省市区页面
    public function city_add(){
    	//判断是否添加 开通区域
    	if(!empty($_POST)){
            $type = I('type');
            $address = I('nickname');
            //判断此地区是否已添加
            $where['address'] = $address;
            $res = M('address')->where($where)->find();
                if(empty($res)){
                    $address_all = $address;
                    $address=explode(',',$address);
                    $data = array(
                     'prov'       => $address[0],
                     'city'       => $address[1],
                     'district'   => $address[2],
                     'type'       => $type,
                     'address'    => $address_all,
                     'statues'     =>1,
                     'createtime' =>time(),
                    );
                $z = M('address')->add($data);
                if($z){
                    $this->success('开通成功');
                }else{
                    $this->error('开通失败');
                }

             }else{
                $this->error('此地区已添加');
             }
            
    	}else{
            //展示省份
             $province = M('province')->select();
             //默认广东省 深圳市 罗湖区
             $default_prov = M('province')->where(array('name'=>'广东省'))->find();
             $default_city =M('city')->where(array('provincecode'=>$default_prov['code']))->select();
             $default_area =M('area')->where(array('citycode'=>440300))->select();

             $this->assign('default_prov',$default_prov);
             $this->assign('default_city',$default_city);
             $this->assign('default_area',$default_area);
             $this->assign('province',$province);
    	     $this->display();	
    	}
    	
    }
  
  //ajax  获取城市列表
    public function city_select(){

         $prov_code = I('prov_code');
         $city = M('city')->where(array('provincecode'=>$prov_code))->select();
         if(!empty($city)){
             $this->ajaxReturn($city,json);
         }else{
             $this->ajaxReturn(1,json);
         }
        
    }

    //ajax 获取区域
    public function  area_select(){
         $city_code = I('city_code');
         $area = M('area')->where(array('citycode'=>$city_code))->select();
         if(!empty($area)){
             $this->ajaxReturn($area,json);
         }else{
            $this->ajaxReturn(1,json);
         }
    }

   


}
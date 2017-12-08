<?php
namespace Smi\Controller;
use Think\Controller;
/**
* 
*/
class MancreditController extends Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	//*******************************************************************************************************************
	//												常见问题管理
	//*******************************************************************************************************************

	
	public function creditlist()
	{
		if($_GET['keyword']){
			$keyword=$_GET['keyword'];			
			$result=M('Member');
			$where['qing_member.wx_nickname|qing_member.realname|qing_member.mobile'] = array(
					'like',"%{$keyword}%"
				) ;
			// $where['openid']=array('like','%'.$keyword.'%');
			// $where['realname']=array('like','%'.$keyword.'%');
			//$where['mobile']=array('like','%'.$keyword.'%');
			// $where['wx_nickname']=array('like','%'.$keyword.'%');
			// $where['_logic']='or';		
		}else{
			$where=1;
		}
		$count  = M('credit_log')->join('qing_member ON qing_credit_log.openid=qing_member.openid')->where($where)->count();// 查询满足要求的总记录数		
		$Page       = new \Think\Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出		
		$data=M('credit_log')->join('qing_member ON qing_credit_log.openid=qing_member.openid')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();	
		//echo M('credit_log')->getlastsql();die;
		$this->assign('data',$data);
		$this->assign('page',$show);
		$this->display();
	}
	
    public function forOrderExcel(){
    	$Model=M("");
        //查询出需要显示的数据
		$list=$Model->table('qing_credit_log c')->join('qing_member m on c.openid=m.openid')->order('c.createtime')->select();
		/*$len=count($list);
		for ($i=0; $i < $len; $i++) { 
			echo $list[$i]['createtime'] = date("Y-m-d H:i",$list[$i]['createtime']);
		}
        die;*/
        error_reporting(E_ALL);//报告所有错误
        date_default_timezone_set('Asia/shanghai');//上海时区
        // 导入phpexcel组件
        require_once './ThinkPHP/Library/Vendor/PHPExcel/Classes/PHPExcel.php';
        //实例化PHPExcel
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator()->setLastModifiedBy()
        ->setTitle('Office 2007 XLSX Document')
        ->setSubject('Office 2007 XLSX Document')
        ->setDescription('Document for Office 2007 XLSX, generated using PHP classes.')
        ->setKeywords('office 2007 openxml php')
        ->setCategory('Result file');
         $styleArray1 = array(
                            'font' => array(
                            'bold' => false,
                            'color'=>array(
                            'argb' => '00000000',
                            ),
                            ),
                            'alignment' => array(
                            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                            ),
                        );
        /*设置行高*/
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('28');
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('23');
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('43');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('12');
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('12');
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth('12');
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth('12');
        // $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth('18');
        $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(30);

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1','openid')
                ->setCellValue('B1','昵称')
                ->setCellValue('C1','姓名')
                ->setCellValue('D1','手机号')
                ->setCellValue('E1','时间')
                ->setCellValue('F1','积分来源/扩展渠道')
                ->setCellValue('G1','积分数量');
            $i=2;   
            foreach($list as $k=>$v){
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i,$v['openid'].' ')
                ->setCellValue('B'.$i,$v['wx_nickname'])
                ->setCellValue('C'.$i,$v['realname'])
                ->setCellValue('D'.$i,$v['mobile'])
                //->setCellValue('E'.$i,$v['createtime'])
                ->setCellValue('E'.$i,date('Y-m-d H:i',$v['createtime']))
                ->setCellValue('F'.$i,$v['title'])
                ->setCellValue('G'.$i,$v['num']);
             $i++;
            }

        $objPHPExcel->getActiveSheet()->setTitle('积分表');
        $objPHPExcel->setActiveSheetIndex(0);

        $fileName =date('_YmdHis');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$fileName.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); 
   }

}


	
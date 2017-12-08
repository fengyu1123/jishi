<?php 
/**
 * TODO 基础分页的相同代码封装，使前台的代码更少
 * @param $count 要分页的总记录数
 * @param int $pagesize 每页查询条数
 * @return \Think\Page
 */
function getpage($count, $pagesize = 10) {
    $p = new Think\Page($count, $pagesize);
    $p->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
    $p->setConfig('prev', '上一页');
    $p->setConfig('next', '下一页');
    $p->setConfig('last', '末页');
    $p->setConfig('first', '首页');
    $p->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
    $p->lastSuffix = false;//最后一页不显示为总页数
    return $p;
}

/**
 * [curl_post description]
 * @param  $url
 * @param  $data
 */
function curl_post($url,$data,$token){
    $url = "http://150.95.142.164:8081/service".$url;
    $headers = array(
      "Content-type: application/json",
    );
    // if($token){
    //     $headers[] = "token:".$token;
    // }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $res = curl_exec($ch);
    curl_close($ch);
    $content = "\r\n\tURL:{$url}\r\n\tDATA:{$data}\r\n\tRETURN:{$res}";
    $max_size = 102400;
    $filename = "curl_log.xml";
    if(file_exists($filename) and (abs(filesize($filename)) > $max_size)) unlink($filename);
    file_put_contents($filename, date('H:i:s')." ".$content."\r\n", FILE_APPEND);
    return $res;
}
/**
 * [curl_get description]
 * @param  $url
 * @param  $token
 */
function curl_get($url,$token){
    $url = "http://150.95.142.164:8081/service".$url;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    if($token){
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("token:".$token));
    }
    $res = curl_exec($ch);
    curl_close($ch);

    $content = "\r\n\tURL:{$url}\r\n\tRETURN:{$res}";
    $max_size = 102400;
    $filename = "curl_log.xml";
    if(file_exists($filename) and (abs(filesize($filename)) > $max_size)) unlink($filename);
    file_put_contents($filename, date('H:i:s')." ".$content."\r\n", FILE_APPEND);

    return $res;
}

function my_substr($str, $index = '', $lenth = '')
{
    //起始位置
    if (!$index) {
        $index = 0;
    } elseif ($index < 0) {
        $index = strlen($str) + $index;
    }
    //截取长度
    if ($lenth) {
        if ($lenth < 0) {
            $len_cur = strlen($str) + $lenth;
        } else {
            $len_cur = $lenth + $index;
        }
    } else {
        $len_cur = strlen($str);
    }
    //编码判断
    $encoding = mb_detect_encoding($str, array('UTF-8', 'GBK'));
    if ($encoding == 'UTF-8') {
        header("Content-type:text/html;charset=UTF-8");
        $zifuji = "UTF-8";
        $step = 3;
    } else {
        header("Content-type:text/html;charset=GBK");
        $zifuji = "GBK";
        $step = 2;
    }
    //数组拼装
    $arr = array();
    if ($index < 0) {
        $index = 0;
    }
    if ($len_cur > strlen($str)) {
        $len_cur = strlen($str);
    }
    for ($i=0; $i < $len_cur; $i++) { 
        $ascii = ord($str{$i});
        if ($ascii < 128) {
            if (($index > 0) && ($i >= $index)) {
                $arr[] = $str[$i];
            } elseif ($index == 0) {
                $arr[] = $str[$i];
            }
        } else {
            if (($index > 0) && ($i >= $index)) {
                $arr[] = $str_now = substr($str, $i, $step);
            } elseif ($index == 0) {
                $arr[] = $str_now = substr($str, $i, $step);
            }
            $i = $i + $step - 1;
        }
    }
    //提示当前字符集
    // echo '<script type="text/javascript">alert("当前字符集为 '.$zifuji.'");</script>';
    //字符串组装
    return implode("", $arr);
}

function GetIp(){  
    $realip = '';  
    $unknown = 'unknown';  
    if (isset($_SERVER)){  
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)){  
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);  
            foreach($arr as $ip){  
                $ip = trim($ip);  
                if ($ip != 'unknown'){  
                    $realip = $ip;  
                    break;  
                }  
            }  
        }else if(isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], $unknown)){  
            $realip = $_SERVER['HTTP_CLIENT_IP'];  
        }else if(isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)){  
            $realip = $_SERVER['REMOTE_ADDR'];  
        }else{  
            $realip = $unknown;  
        }  
    }else{  
        if(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), $unknown)){  
            $realip = getenv("HTTP_X_FORWARDED_FOR");  
        }else if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), $unknown)){  
            $realip = getenv("HTTP_CLIENT_IP");  
        }else if(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), $unknown)){  
            $realip = getenv("REMOTE_ADDR");  
        }else{  
            $realip = $unknown;  
        }  
    }  
    $realip = preg_match("/[\d\.]{7,15}/", $realip, $matches) ? $matches[0] : $unknown;  
    return $realip;  
}  
 ?>
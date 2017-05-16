<?php
/**
 * 用来检查一些输入格式是否符合特定标准
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2013-10-29
 */
!defined('IN_FUNREST') && exit('Access Denied');

class checkChar {
    
    function __construct(){
        
    }
    /**
     * 统一的验证，验证规则与前台验证框架类似
     * ip
     * mail
     * url
     * zip
     * username
     * passwod
     * phone
     * mobile
     * date
     * datetime
     * qq
     * chinese-[min]-[max]
     * alpha-[min]-[max]
     * alnum-[min]-[max]
     * int-[min]-[max]
     * str-[min]-[max]
     * @param string $str    验证类型
     * @param string $val    验证值
     */
    function checkNow($str,$val){
        if($val=='') return true;
        $arr = explode('-',$str);
        
        switch($arr[0]){
            case 'must':
                return (strlen($val) > 0);
                break;
            case 'uuid':
                return (strlen($val) == 36);
                break;
            case 'str'://str-min-max
                $min = (isset($arr[1]))?$arr[1]-1:-1;
                $max = (isset($arr[2]))?$arr[2]+1:50000;
                $len = mb_strlen($val);
                return ($len > $min && $len < $max);
                break;
            case 'str2'://str2-min-max
                $min = (isset($arr[1]))?$arr[1]-1:-1;
                $max = (isset($arr[2]))?$arr[2]+1:50000;
                $val = iconv('utf-8','gbk',$val);
                $len = strlen($val);
                
                return ($len > $min && $len < $max);
                break;
            case 'int'://int-min-max
                $min = (isset($arr[1]))?$arr[1]-1:-1;
                $max = (isset($arr[2]))?$arr[2]+1:9999999;
                return ($val > $min && $val < $max);
                break;
            case 'alpha'://验证字母 alpha-min-max
                $min = (isset($arr[1]))?$arr[1]-1:-1;
                $max = (isset($arr[2]))?$arr[2]+1:90000;
                $len = mb_strlen($val);
                return (ctype_alpha($val))?($len > $min && $len < $max):false;
                break;
            case 'alnum'://验证字母+数字
                $min = (isset($arr[1]))?$arr[1]-1:-1;
                $max = (isset($arr[2]))?$arr[2]+1:90000;
                $len = mb_strlen($val);
                return (ctype_alnum($val))?($len > $min && $len < $max):false;
                break;
            case 'username':
                $gval = iconv('utf-8','gbk',$val);
                $len = strlen($gval);
                if($len < 5 || $len > 12){
                    return false;
                    break;
                }
                return preg_match("/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u",$val);
                break;
            case 'password':
                return preg_match('/^[a-zA-Z0-9_]{5,12}$/',$val);
                break;
            case 'date'://yyyy-mm-dd
                list($year, $month, $day) = sscanf($val, '%d-%d-%d');
                return checkdate($month, $day, $year);
                break;
            case 'datetime': //yyyy-mm-dd hh:ii:ss
                list($year, $month, $day, $h, $m, $s) = sscanf($val, '%d-%d-%d %d:%d:%d');
                if(!checkdate($month, $day, $year)) return false;
                return ($h>-1 && $h<25) && ($m>-1 && $m<61) && ($s>-1 && $s<61);
                break;
            case 'ip':
                //return ip2long($val);
                $val = trim($val);
                return (preg_match("/^\d{1,3}+\.\d{1,3}+\.\d{1,3}+\.\d{1,3}+$/",$val))? true:false;
                break;
            case 'email':
                return preg_match('/\w{1,}[@][\w\-]{1,}([.]([\w\-]{1,})){1,3}$/',$val);
                break;
            case 'url':
                return preg_match('/^([a-zA-Z0-9_-]*[.]){1,3}[a-zA-Z0-9_-]{2,3}$/', $val);
                break;
            case 'zip':
                return preg_match('/^[0-9]{6}$/',$val);
                break;
            case 'qq':
                return preg_match('/^[0-9]{5,15}$/',$val);
                break;
            case 'idcard':
                return preg_match('/^([a-zA-Z0-9]{15}|[a-zA-Z0-9]{18})$/',$val);
                break;
            case 'phone':
                return preg_match('/^((0[1-9]{3})?(0[12][0-9])?[-])?\d{7,8}$/',$val);
                break;
            case 'mobile':
                return preg_match('/^(13|15|18)+[0-9]{9}$/',$val);
                break;
            case 'chinese': //chinese-min-max
                $min = (isset($arr[1]))?$arr[1]:0;
                $max = (isset($arr[2]))?$arr[2]:50000;
                return preg_match('/^[\xB0-\xF7][\xA1-\xFE]{'.$min.','.$max.'}$/',$val);
                break;
        }
    }
}
?>
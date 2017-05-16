<?php
/**
 * 短信验证码模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class sms_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $this->maxNum = 3;
    }
    
    //添加
    function addNow($phone, $sort = 0, $appid){
        //避免重复下发
        $sql = "SELECT `smsid` FROM `the_sms` WHERE `phone` = ".$phone." AND `sort` = ".$sort." AND `status` = 0 AND `flag` = 1 ORDER BY `smsid` DESC LIMIT 1";
        $res = $this->db->getX($sql);
        if(count($res)){    
            $smsid = $res[0]['smsid'];
            return array("msg" => "验证码已发送！", "smsid" => $smsid );   
        }
        
        //避免恶意下发
        $sql = "SELECT COUNT(*) AS `num` FROM `the_sms` WHERE `phone` = ".$phone." AND `sort` = ".$sort." AND `add_at` > '".strtotime(date('Y-m-d'))."' ";
        $res = $this->db->getX($sql);
        $num = $res[0]['num'];
        if($num >= $this->maxNum ){
            error(406,'今日下发的验证码数量已达到上限！');
        }
        
        $data = array(
            "appid" => $appid,
            "phone" => $phone,
            "sort" => $sort,
            "code" => rand(1000,9999),
            "add_at" => time()
        );
        
        $smsid = $this->db->add('the_sms',$data);   //写入记录
        if( $smsid){
            return array("msg" => "验证码已发送！", "smsid" => $smsid );    
        }else{
            error(406,'验证码发送失败！请重试！');
        }
    }
    
    //验证手机验证码
    function verifyNow($phone, $sms_code = ''){
        $sql = "SELECT 1 FROM `the_sms` WHERE `phone` = ".$phone." AND `code` = '".$sms_code."' AND `status` != 2 AND `flag` = 1 ";
        $res = $this->db->getX($sql);
        return count($res);
    }
    
    //使用验证码注册
    function useNow($phone, $sms_code = ''){
        $sql = "UPDATE `the_sms` SET `status` = 2 WHERE `phone` = ".$phone." AND `code` = '".$sms_code."' AND `status` != 2 AND `flag` = 1 ";
        return $this->db->updateX($sql);
    }
    
}
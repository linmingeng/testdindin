<?php
/**
 * 应用密钥模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2016-07-07
 */
class app_key_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $filedsArr = array(
            "`appid`",
            "`appkey`",
            "`secretkey`"
        );
        
        $this->fileds = implode(",",$filedsArr);
        
    }
    
    //[portal]获取详情
    function getDetail($appkey){
        $data = array();
        $sql = "SELECT ".$this->fileds." FROM `the_app_key` WHERE `appkey` = '".$appkey."' ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
        }
        return $data;
    }
    
    //[portal]添加
    function addNow($data){
        return $this->db->add('the_app_key',$data);
    }
    
    //[portal]更新
    function updateNow($userid, $appid, $secretkey){
        $sql = "UPDATE `the_app_key` SET `secretkey` = '".$secretkey."' WHERE `userid` = '".$userid."' AND `appid` = '".$appid."' ";
        return $this->db->updateX($sql);
    }
}
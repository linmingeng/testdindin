<?php
/**
 * 应用配置模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2016-07-10
 */
class app_conf_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $filedsArr = array(
            "`default_city`", 
            "`guider_name`",
            "`guider_avatar`",
            "`show_discount`", 
            "`reduce_late`",
            "`no_goods_txt`",
            "`tags`", 
            "`tabs`",  
            "`home`", 
            "`member`",
            "`trader`",
            "`coupon`",
            "`hongbao`",
            "`chain_store`",
        );
        $this->filedsArr = $filedsArr;
        $this->fileds = implode(",",$filedsArr);
        
        $this->pageSize = 20;
        
    }
    
    //获取配置信息
    function getConf($appid = 0, $fileds = ''){
        if(!is_array($fileds)){
            if($fileds){
                $fileds = array($fileds);
            }else{
                $fileds = array();
            }
        }
        if(count($fileds)){
            foreach($fileds as $k => $filed){
                if(!in_array('`'.$filed.'`', $this->filedsArr)){
                    unset($fileds[$k]);
                }
            }
            if(count($fileds)){
                $select_fileds = implode(",",$fileds);
            }
        }
        if(!$select_fileds){
            $select_fileds = $this->fileds;
        }
        $data = array();
        $sql = "SELECT ".$select_fileds." FROM `the_app_conf` WHERE `appid` = '".$appid."' AND `flag` = 1 ";
        $ret = $this->db->getX($sql);
        if(count($ret) && is_array($ret[0])){
            $jsonFileds = array(
                "default_city", 
                "tags", 
                "tabs",  
                "home", 
                "member",
                "trader",
                "coupon",
                "hongbao",
                "chain_store",
            );
            foreach($ret[0] as $k => $v){
                if(in_array($k, $jsonFileds)){
                    $data[$k] = myjson_decode($v);
                }else{
                    $data[$k] = $v;
                }
            }
        }
        return $data;
    }
    
}
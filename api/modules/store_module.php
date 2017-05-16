<?php
/**
 * 单页模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class store_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       $filedsArr = array(
            'storeid',
            'appid',
            'name',
            'origin_place',
            'icon',
            'banner',
            'bg_image',
            'groupids',
            'rate_service',
            'rate_real',
            'rate_speed',
            'star',
            'linkman',
            'sex',
            'tel',
            'verify_image1',
            'verify_image2',
            'verify_image3',
            'verify_image4',
            'verified',
            'best',
            'ordernum',
            'flag'

        );
        
        $this->fileds = implode(",",$filedsArr);
        
        $this->pageSize = 10;
    }
    
    //获取单页内容
    function getStore($appid = 0,$storeid = 0){
        $whereStr = " `flag` = 1";
        $whereStr .= " AND storeid=".$storeid;
        if($appid){
            $whereStr .= " AND appid=".$appid;
        }
        $sql = "SELECT ".$this->fileds." FROM `the_store` WHERE ".$whereStr;
        $ret = $this->db->getX($sql);
        return $ret;
    }
    
}
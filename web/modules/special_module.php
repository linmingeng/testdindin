<?php
/**
 * 专题活动模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-09-01
 */
class special_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $filedsArr = array(
            "`specialid`",
            "`title`",
            "`name1`",
            "`image1`",
            "`ids1`",
            "`name2`",
            "`image2`",
            "`ids2`",
            "`name3`",
            "`image3`",
            "`ids3`",
            "`name4`",
            "`image4`",
            "`ids4`",
            "`name5`",
            "`image5`",
            "`ids5`",
            "`special_top`",
            "`special_bottom`",
        );
        
        $this->fileds = implode(",",$filedsArr);
        
    }
    
    //获取专题列表
    function getSpecials($appid){
        $appid = (int)$appid;
        $whereStr = "`appid` IN (0,".$appid.") ";
        $sql = "SELECT ".$this->fileds." FROM `the_special` WHERE ".$whereStr." AND `flag` = 1 ORDER BY `ordernum` DESC LIMIT 12";
        return $this->db->getX($sql);
    }
    
    //获取专题信息
    function get($specialid){
        $specialid = (int)$specialid;
        $whereStr = "`specialid` = ".$specialid." ";
        $sql = "SELECT ".$this->fileds." FROM `the_special` WHERE ".$whereStr." AND `flag` = 1 ";
        $res = $this->db->getX($sql);
        if(count($res)){
            return $res[0];    
        }
    }
    
}
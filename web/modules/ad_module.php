<?php
/**
 * 广告模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-09-01
 */
class ad_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $filedsArr = array(
            "`ad_pcid`",
            "`title`",
            "`channel`",
            "`type`",
            "`origin_place`",
            "`storeid`",
            "`groupid`",
            "`begin_at`",
            "`end_at`",
            "`is_showname`",
            "`name1`",
            "`image1`",
            "`url1`",
            "`name2`",
            "`image2`",
            "`url2`",
            "`name3`",
            "`image3`",
            "`url3`",
            "`name4`",
            "`image4`",
            "`url4`",
            "`name5`",
            "`image5`",
            "`url5`",
        );
        $this->fileds = implode(",",$filedsArr);
    }
    
    //获取广告列表
    function getAds($appid,$groupid=0,$storeid=0,$channel=0,$origin_place=0){
        $appid = (int)$appid;
        $where_arr[] = "`appid` IN (0,".$appid.") ";
        $where_arr[] = " `groupid` =".$groupid;
        $where_arr[] = " `storeid` =".$storeid;
        if($channel > 0){
            $where_arr[] = " `channel` =".$channel;
            if($channel == 4){
                $where_arr[] = " `origin_place`=".$origin_place;
            }
        }
        $whereStr = implode(' AND ',$where_arr);
        $sql = "SELECT ".$this->fileds." FROM `the_ad_pc` WHERE ".$whereStr." AND `flag` = 1 ORDER BY `ordernum` DESC";
        return $this->db->getX($sql);
    }
    
    //获取广告信息
    function get($ad_pcid){
        $ad_pcid = (int)$ad_pcid;
        $whereStr = "`ad_pcid` = ".$ad_pcid." ";
        $sql = "SELECT ".$this->fileds." FROM `the_ad_pc` WHERE ".$whereStr." AND `flag` = 1 ";
        $res = $this->db->getX($sql);
        if(count($res)){
            return $res[0];    
        }
    }
    
}
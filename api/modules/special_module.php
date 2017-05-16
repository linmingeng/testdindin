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
            "`type`",
            "`origin_place`",
            "`storeid`",
            "`groupid`",
            "`is_home`",
            "`is_overseas`",
            "`image`",
            "`info`",
            "`begin_at`",
            "`end_at`",
            "`is_showname`",
            "`name1`",
            "`image1`",
            "`url1`",
            "`ids1`",
            "`name2`",
            "`image2`",
            "`url2`",
            "`ids2`",
            "`name3`",
            "`image3`",
            "`url3`",
            "`ids3`",
            "`name4`",
            "`image4`",
            "`url4`",
            "`ids4`",
            "`name5`",
            "`image5`",
            "`url5`",
            "`ids5`",
            "`special_top`",
            "`special_bottom`"
        );
        
        $this->fileds = implode(",",$filedsArr);
        
    }
    
    //获取专题列表
    function getSpecials($appid,$groupid=0,$storeid=0,$style=0,$origin_place=0){
        $appid = (int)$appid;
        $where_arr[] = "`appid` IN (0,".$appid.") ";
        switch ($style){
            case 1://首页/分类页
                   if($groupid > 0){//分类页
                       $where_arr[] = " `groupid`=".$groupid;
                   }else{//首页
                       $where_arr[] = " `is_home`=1";
                   }
                break;
            case 2://店铺专题
                $where_arr[] = " `storeid`=".$storeid;
                break;
            case 3://国家馆首页专题
                $where_arr[] = " `is_overseas`=1";
                break;
            case 4://国家馆首页专题
                $where_arr[] = " `origin_place`=".$origin_place;
                break;
        }
        $whereStr = implode(' AND ' ,$where_arr);
        $sql = "SELECT ".$this->fileds." FROM `the_special` WHERE ".$whereStr." AND `flag` = 1 ORDER BY `ordernum` DESC";
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
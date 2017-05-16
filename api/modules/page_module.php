<?php
/**
 * 单页模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class page_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $filedsArr = array(
            "`pageid`",
            "`title`",
            "`content`",
            "`bg_color`",
            "`bg_img`",
            "`padding`",
            "`share`",
            "`hide_bar`",
            "`desc`",
            "`img`",
            "`add_at`", 
        );
        
        $this->fileds = implode(",",$filedsArr);
        
        $this->pageSize = 10;
    }
    
    //获取单页内容
    function getPage($appid, $pageid){
        if(is_numeric($pageid)){
            $whereStr = " `pageid` = '".$pageid."' ";    
        }else{
            $whereStr = " `type` = '".$pageid."' ";    
        }
        $data = array();
        $sql = "SELECT ".$this->fileds." FROM `the_page` WHERE `appid` = '".$appid."' AND ".$whereStr." AND `flag` = 1";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
        }
        return $data;
    }
    
}
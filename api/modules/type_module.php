<?php
/**
 * 应用分类模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class type_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $filedsArr = array(
            "`typeid`",
            "`name`",
            "`ver`"
        );
        
        $this->fileds = implode(",",$filedsArr);
        
    }
    
    //获取应用类型
    function getAllType(){
        $data = array();
        $sql = "SELECT ".$this->fileds." FROM `the_type` WHERE `flag` = 1 ORDER BY `ordernum` DESC,`typeid` DESC ";
        $ret = $this->db->getX($sql);
        return $ret;
    }
    
    //[portal]获取详情
    function getDetail($typeid = 0){
        $data = array();
        $sql = "SELECT ".$this->fileds." FROM `the_type` WHERE `typeid` = '".$typeid."' AND `flag` = 1 ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
        }
        return $data;
    }
}
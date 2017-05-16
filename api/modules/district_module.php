<?php
/**
 * 区域模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-10-29
 */
class district_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
    }
    
    //获取列表
    function getList($cityid = 0){
        if($cityid){
            $whereStr = " `cityid` = '".$cityid."' AND ";
        }else{
            $whereStr = "";
        }
        $sql = "SELECT `districtid`,`provinceid`,`cityid`,`name`,`spell`,`zip` FROM `the_district` WHERE ".$whereStr." `flag` = 1 AND `check` = 1 ORDER BY `ordernum` DESC,`districtid` DESC";
        return $this->db->getX($sql);
    }
    
    function get($districtid = 0){
        $sql = "SELECT `name` FROM `the_district` WHERE `districtid` = '".$districtid."' ";
        $res = $this->db->getX($sql);
        if(is_array($res) && isset($res[0])){
            return $res[0]['name'];
        }
    }
}
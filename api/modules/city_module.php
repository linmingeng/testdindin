<?php
/**
 * 城市模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-10-29
 */
class city_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
    }
    
    //获取列表
    function getList($provinceid = 0){
        if($provinceid){
            $whereStr = " `provinceid` = '".$provinceid."' AND ";
        }else{
            $whereStr = "";
        }
        $sql = "SELECT `cityid`,`provinceid`,`cityid`,`name`,`spell`,`lng`,`lat` FROM `the_city` WHERE ".$whereStr." `flag` = 1 AND `check` = 1 ORDER BY `ordernum` DESC,`cityid` DESC";
        return $this->db->getX($sql);
    }
    
    function get($cityid = 0){
        $sql = "SELECT `name` FROM `the_city` WHERE `cityid` = '".$cityid."' ";
        $res = $this->db->getX($sql);
        if(is_array($res) && isset($res[0])){
            return $res[0]['name'];
        }
    }
}
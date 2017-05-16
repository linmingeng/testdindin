<?php
/**
 * 省份模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-10-29
 */
class province_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
    }
    
    //获取全部
    function getList(){
        $sql = "SELECT `provinceid`,`name`,`spell` FROM `the_province` WHERE `flag` = 1 AND `check` = 1 ORDER BY `ordernum` DESC,`provinceid` DESC";
        return $this->db->getX($sql);
    }
    
    function get($provinceid = 0){
        $sql = "SELECT `name` FROM `the_province` WHERE `provinceid` = '".$provinceid."' ";
        $res = $this->db->getX($sql);
        if(is_array($res) && isset($res[0])){
            return $res[0]['name'];
        }
    }               
}
<?php
/**
 * �汾���ģ��
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2013-10-29
 */
class versions_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
    }
    
    //��ҳ���°汾��Ϣ  plateform 0=Andriod; 1=iOS; 
    function getNewVersion($plateform = 0){
        $sql = "SELECT `version`,`url` FROM `the_version` WHERE `plateform` = '".$plateform."' AND `flag` = 1 ORDER BY `versionid` DESC";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            return $ret[0];
        }
        return array();
    }
    
}
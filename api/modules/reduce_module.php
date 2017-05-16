<?php
/**
 * 满减活动模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-09-01
 */
class reduce_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $filedsArr = array(
            "`reduceid`",
            "`type`",
            "`name`",
            "`appid`",
            "`consume`",
            "`money`",
            "`start_at`",
            "`end_at`"
        );
        
        $this->fileds = implode(",",$filedsArr);
        
    }
    
    //获取满减活动列表
    function getList($appid){
        $data = array();
        $sql = "SELECT ".$this->fileds." FROM `the_reduce` WHERE `appid`='".$appid."' AND `flag` = 1 AND `check` = 1 ORDER BY `type` ASC";
        $reduces = $this->db->getX($sql);
        if(count($reduces)){
            foreach($reduces as $res){
                if(($res['start_at'] == 0 && $res['end_at'] == 0) || ($res['start_at'] > 0 && $res['end_at'] > 0 && time() > $res['start_at'] && time() < $res['end_at'] ) ){
                    $data[] = $res;
                }
            }
        }
        return $data;
    }
    
}
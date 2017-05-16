<?php
/**
 * 分销商等级模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class trader_level_module {
    
    function __construct() {
        global $db;
        $this->db = $db;
        $filedsArr = array(
            "`trader_levelid`",
            "`auto_up`",
            "`level`",
            "`color`",
            "`title`",
            "`order_rate`",
            "`sub_rate`",
            "`exp`"
        );
        
        $this->fileds = implode(",",$filedsArr);
    }
    
    //获取用户等级 TODO: cache
    function getTraderLevels($appid){
        $data = array();
        $sql = "SELECT ".$this->fileds." FROM `the_trader_level` WHERE `appid` = '".$appid."'";
        $res = $this->db->getX($sql);
        if(count($res)){
            foreach($res as $k => $v){
                $data[$v['level']] = $v;
                unset($data[$v['level']]['trader_levelid']);
            }
        }
        return $data;
    }
    
}
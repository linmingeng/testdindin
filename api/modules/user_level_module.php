<?php
/**
 * 用户等级模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class user_level_module {
    
    function __construct() {
        global $db;
        $this->db = $db;
        $filedsArr = array(
            "`user_levelid`",
            "`auto_up`",
            "`level`",
            "`color`",
            "`title`",
            "`buy_rate`",
            "`exp`",
            "`deduct_exp`",
            "`deduct_score`",
            "`birthday`",
            "`lbs_sign`",
            "`sign`",
            "`continuous`",
            "`auto_sign`",
            "`invite_score`"
        );
        
        $this->fileds = implode(",",$filedsArr);
    }
    
    //获取用户等级 TODO: cache
    function getUserLevels($appid){
        $data = array();
        $sql = "SELECT ".$this->fileds." FROM `the_user_level` WHERE `appid` = '".$appid."'";
        $res = $this->db->getX($sql);
        if(count($res)){
            foreach($res as $k => $v){
                $data[$v['level']] = $v;
                unset($data[$v['level']]['user_levelid']);
            }
        }
        return $data;
    }
    
    //获取用户等级信息 TODO: cache
    function getLevelConf($appid,$level){
        $data = array();
        $sql = "SELECT ".$this->fileds." FROM `the_user_level` WHERE `appid` = '".$appid."' AND `level` = '".$level."'";
        $res = $this->db->getX($sql);
        if(count($res)){
            return $res[0];
        }
        return $data;
    }
}
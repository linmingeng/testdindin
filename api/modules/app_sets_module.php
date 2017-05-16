<?php
/**
 * 应用模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class app_sets_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
    }
    
    //[portal]获取应用配置信息
    function getAppSets($appid){
        global $user_info;
        $userid = $user_info['userid'];
        if(!$userid){
            return ;
        }
        $data = array();
        $sql = "SELECT `appid`,`name`,`logo`,`domain`,`industry` FROM `the_app` WHERE `appid` = '".$appid."' AND `userid` = '".$userid."' AND `flag` = 1 ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
        }else{
            return ;    
        }
        $sql = "SELECT * FROM `the_app_sets` WHERE `appid` = '".$appid."' AND `userid` = '".$userid."' AND `flag` = 1 ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            if(isset($ret[0]['name']) && !$ret[0]['name']){
                unset($ret[0]['name']);
            }
            if(isset($ret[0]['domain']) && !$ret[0]['domain']){
                unset($ret[0]['domain']);
            }
            $data = array_merge($data,$ret[0]);
        }
        return $data;
    }
    
    //[portal]修改应用配置
    function updateAppSets($appid, $setSqlArr, $data, $name){
        global $user_info;
        if(!$user_info['userid'] || !$appid || !is_array($setSqlArr) || !count($setSqlArr) || !is_array($data) || !count($data)){
            return 0;    
        }
        $userid = $user_info['userid'];
        $sql = "SELECT `app_setsid` FROM `the_app_sets` WHERE `appid` = '".$appid."' AND `userid` = '".$userid."' AND `flag` = 1 ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $sql = "UPDATE `the_app_sets` SET ".implode(',',$setSqlArr)." WHERE `app_setsid` = '".$ret[0]['app_setsid']."' ";
            return $this->db->updateX($sql);
        }
        if(!$name){
            $sql = "SELECT `name` FROM `the_app` WHERE `appid` = '".$appid."' AND `userid` = '".$userid."' AND `flag` = 1 ";
            $ret = $this->db->getX($sql);
            if(!count($ret)){
                return ;    
            }
            $name = $ret[0]['name'];
        }
        $data['userid'] = $userid;
        $data['appid'] = $appid;
        $data['name'] = $name;
        $data['update_at'] = time();
        $data['add_at'] = time();
        $data['flag'] = 1;
        return $this->db->add('the_app_sets', $data);
    }
    
    //[portal]添加
    function addNow($data){
        return $this->db->add('the_app_sets',$data);
    }
    
    //[portal]获取配置信息
    function getTypeInfo($appid,$userid){
        $data = array();
        $sql = "SELECT `appid`,`typeid`,`ver` FROM `the_app_sets` WHERE `appid` = '".$appid."' AND `userid` = '".$userid."' AND `flag` = 1 ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
        }
        return $data;
    }
    
    //获取微信登录、分享、支付、极光推送等开关信息
    function getTonggles($appid){
        $data = array();
        $sql = "SELECT `wx_login`,`wx_pay`,`wx_share`,`mobile_login`,`mobile_pay`,`mobile_share`,`web_login`,`jpush`,`quick_login` FROM `the_app_sets` WHERE `appid` = '".$appid."' AND `flag` = 1 ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
        }
        return $data;
    }
    
}
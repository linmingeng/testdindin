<?php
/**
 * session
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2016-08-23
 */
class session {
    
    function __construct() {
    
    }
    
    //设置 session
    function set($userid, $auth, $ttl){
        global $cache;
        mySetcookie('auth',$auth,$ttl);
        return $cache->setex('sid:'.md5($auth), $ttl, $userid);
    }
    
    //获取 session
    function get($key){
        global $cache;
        if($key){
            return $cache->getData('sid:'.$key);
        }
    }
    
    //删除 session
    function del($key){
        global $cache;
        mySetcookie('auth','',0);
        if($key){
            return $cache->del('sid:'.$key);
        }
    }
    
}
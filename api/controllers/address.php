<?php
require_once BASE_PATH.'/modules/address_module.php';
/**
 * 收货地址控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class address {
    
    function __construct(){
        $this->address_module = new address_module();
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/address/list&page=1
    function list_get(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        
        $appid = (int)request('appid');
        $page = (int)request('page');
        $page = max($page,1);
        $key = 'address:'.$appid.':'.$user_info['userid'].':'.$page;
        $cache->get($key);                                  //获取缓存，处理304
        
        $data = $this->address_module->getAddress($appid, $user_info['userid'], $page); 
        $cache->set($key,$data);                             //设置缓存，增加额外的header
        return $data;
    } 
    
    //调用网址：[POST] http://localhost/api/index.php?/address/add
    function add_post(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        $appid = (int)request('appid');
        $key = 'address:'.$appid.':'.$user_info['userid'].':1';
        $cache->del($key);                                  //清除缓存
        
        $ret = $this->address_module->addNow($appid, $user_info['userid']); 
        if(is_array($ret) && $ret['addressid']){
            return $this->address_module->getDetail($ret['addressid']); 
        }
        return $ret;
    } 
    
    //调用网址：[POST] http://localhost/api/index.php?/address/del/aid/1
    function del_post(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        
        $appid = (int)request('appid');
        $key = 'address:'.$appid.':'.$user_info['userid'].':1';
        $cache->del($key);                                  //清除缓存
        
        $addressid = (int)request('addressid');
        return $this->address_module->delNow($appid, $user_info['userid'], $addressid); 
    } 
    
    
}
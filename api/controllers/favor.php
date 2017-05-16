<?php
require_once BASE_PATH.'/modules/favor_module.php';
/**
 * 收藏控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class favor {
    
    function __construct(){
        $this->favor_module = new favor_module();
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/favor/list&page=1
    function list_get(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        
        $page = (int)request('page');
        $page = max($page,1);
        $key = 'favor:'.$user_info['userid'].':'.$page;
        $cache->get($key);                                  //获取缓存，处理304
        
        $data = $this->favor_module->getFavor($page); 
        $cache->set($key,$data);                             //设置缓存，增加额外的header
        return $data;
    } 
    
    //调用网址：[POST] http://localhost/api/index.php?/favor/add/appid/1
    function add_post(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        
        $key = 'favor:'.$user_info['userid'].':1';
        $cache->del($key);                                  //获取缓存，处理304
        
        $key = 'favor_status:'.$user_info['userid'];
        $cache->del($key);                                  //获取缓存，处理304
        
        $appid = (int)request('appid');
        return $this->favor_module->addNow($appid); 
    } 
    
    //调用网址：[POST] http://localhost/api/index.php?/favor/del/favorid/1
    function del_post(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            error(403,'请重新登录！');
        }
        
        $key = 'favor:'.$user_info['userid'].':1';
        $cache->del($key);                                  //获取缓存，处理304
        
        $key = 'favor_status:'.$user_info['userid'];
        $cache->del($key);                                  //获取缓存，处理304
        
        $favorid = (int)request('favorid');
        return $this->favor_module->delNow($favorid); 
    } 
    
    //调用网址：[GET] http://localhost/api/index.php?/favor/status
    function status_get(){
        global $cache,$user_info;
        if(!$user_info['userid']){
            return array('results' => array());
            //error(403,'请重新登录！');
        }
        
        $key = 'favor_status:'.$user_info['userid'];
        $cache->get($key);                                  //获取缓存，处理304
        
        $data = $this->favor_module->getFavorStatus(); 
        $cache->set($key,$data);                             //设置缓存，增加额外的header
        return $data;
    } 
}
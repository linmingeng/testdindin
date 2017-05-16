<?php
require_once BASE_PATH.'/modules/city_module.php';
/**
 * 城市控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-10-29
 */
class city {
    
    function __construct(){
        $this->city_module = new city_module();
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/city/list?provinceid=1
    function list_get(){
        global $cache,$controller,$method;
        
        $provinceid = (int)request('provinceid');
        
        $key = $controller.':'.$method.':'.$provinceid;
        $cache->get($key);                                  //获取缓存，处理304
        
        $data = $this->city_module->getList($provinceid); 
        $cache->set($key,$data);                            //设置缓存，增加额外的header
        return $data;
    } 
    
    
    
}
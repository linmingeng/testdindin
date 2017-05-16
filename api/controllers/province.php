<?php
require_once BASE_PATH.'/modules/province_module.php';
/**
 * 省份控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-10-29
 */
class province {
    
    function __construct(){
        $this->province_module = new province_module();
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/province/list
    function list_get(){
        global $cache,$controller,$method;
        $key = $controller.':'.$method;
        $cache->get($key);                                  //获取缓存，处理304
        
        $data = $this->province_module->getList(); 
        $cache->set($key,$data);                             //设置缓存，增加额外的header
        return $data;
    } 
    
    
}
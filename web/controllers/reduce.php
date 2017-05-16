<?php
require_once BASE_PATH.'/modules/reduce_module.php';
/**
 * 满减活动控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class reduce {
    
    function __construct(){
        $this->reduce_module = new reduce_module();
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/reduce/list
    function list_get(){
        global $cache,$controller,$method;
        $appid = (int)request('appid');
        $key = $controller.':'.$method.':'.$appid;
        $cache->get($key);                                  //获取缓存，处理304
        
        $data = $this->reduce_module->getList($appid); 
        $cache->set($key,$data);                            //设置缓存，增加额外的header
        return $data;
    } 
    
    
}
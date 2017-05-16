<?php
require_once BASE_PATH.'/modules/page_module.php';
/**
 * 单页控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class page {
    
    function __construct(){
        $this->page_module = new page_module();
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/page/detail/type/1|home
    function detail_get(){
        global $cache,$controller,$method;
        
        $appid = (int)request('appid');
        $pageid = request('pageid');
        if(!$pageid){
            $pageid = 'default';    
        }
        $key = $controller.':'.$method.':'.$appid.':'.$pageid;
        $cache->get($key);                                  //获取缓存，处理304
        
        $res = $this->page_module->getPage($appid, $pageid);
        $cache->set($key,$res);                             //设置缓存，增加额外的header
        return $res;
    } 
    
}
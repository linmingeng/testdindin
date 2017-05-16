<?php
require_once BASE_PATH.'/modules/community_module.php';
/**
 * 社区控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-10-29
 */
class community {
    
    function __construct(){
        $this->community_module = new community_module();
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/community/list?districtid=1&cityid=1&page=1
    function list_get(){
        global $cache,$controller,$method;
        
        $cityid = (int)request('cityid');  
        $districtid = (int)request('districtid');  
        $page = (int)request('page');
        $page = max($page,1);
        
        $key = $controller.':'.$method.':'.$cityid.':'.$districtid.':'.$page;
        $cache->get($key);                                  //获取缓存，处理304
        
        $data = $this->community_module->getList($cityid, $districtid, $page); 
        $cache->set($key,$data);                            //设置缓存，增加额外的header
        return $data;
    } 
    
}
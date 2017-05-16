<?php
require_once BASE_PATH.'/modules/district_module.php';
/**
 * 区域控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-10-29
 */
class district {
    
    function __construct(){
        $this->district_module = new district_module();
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/district/list?cityid=1
    function list_get(){
        global $cache,$controller,$method;
        
        $cityid = (int)request('cityid');  
        
        $key = $controller.':'.$method.':'.$cityid;
        $cache->get($key);                                  //获取缓存，处理304
        
        $district = $this->district_module->getList($cityid); 
        $data = array('district' => $district);
        $cache->set($key,$data);                            //设置缓存，增加额外的header
        return $data;
    } 
    
    
    
}
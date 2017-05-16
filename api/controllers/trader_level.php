<?php
require_once BASE_PATH.'/modules/trader_level_module.php';
/**
 * 分销商等级相关控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-20
 */
class trader_level {
    
    //获取用户等级列表, 调用网址：[GET] http://localhost/api/index.php?/trader_level/list&appid=1
    function list_get(){
        global $cache;
        $appid = (int)request('appid');
        $key = 'trader_level_list:'.$appid;

        $cache->get($key);                                  //获取缓存，处理304
        $trader_level_module = new trader_level_module();
        $data = $trader_level_module->getTraderLevels($appid); 
        $cache->set($key,$data);                             //设置缓存，增加额外的header
        return $data;
    } 
    
}
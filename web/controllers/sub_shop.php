<?php
require_once BASE_PATH.'/modules/sub_shop_module.php';

/**
 * 连锁店控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class sub_shop {
    
    function __construct(){
        $this->sub_shop_module = new sub_shop_module();
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/sub_shop/list?appid=1&page=1
    function list_get(){
        global $cache,$controller,$method;
        
        $appid = (int)request('appid');
        $page = (int)request('page');
        $page = max($page,1);
        $key = $controller.':'.$method.':'.$appid.':'.$page;
        $cache->get($key);                                  //获取缓存，处理304
        
        $data = $this->sub_shop_module->getSubShop($appid,$page); 
        $cache->set($key,$data);                             //设置缓存，增加额外的header
        return $data;
    } 
    
    //调用网址：[GET] http://localhost/api/index.php?/sub_shop/detail?sub_shopid=3
    function detail_get(){
        global $cache,$controller,$method;
        
        $sub_shopid = (int)request('sub_shopid');
        $key = $controller.':'.$method.':'.$sub_shopid;
        $cache->get($key);                                  //获取缓存，处理304
        
        $result = $this->sub_shop_module->getDetail($sub_shopid);
        if(empty($result)){
            return ;
        }
        $cache->set($key,$result);                          //设置缓存，增加额外的header
        return $result;
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/sub_shop/near_list?appid=1&lng=xxx&lat=xxx
    function near_list_get(){
        
        $appid = (int)request('appid');
        $lng = request('lng');
        $lat = request('lat');
        
        return $this->sub_shop_module->getNearSubShops($appid, $lat, $lng);
    }
    
}
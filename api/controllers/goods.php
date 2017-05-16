<?php
require_once BASE_PATH.'/modules/goods_module.php';
/**
 * 商品控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-10-29
 */
class goods {
    
    function __construct(){
        $this->goods_module = new goods_module();
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/goods/detail/goodsid/1
    function detail_get(){
        global $cache,$controller,$method;
        
        $goodsid = (int)request('goodsid');
        
        $key = $controller.':'.$method.':'.$goodsid;
        $cache->get($key);                                  //获取缓存，处理304
        
        $data = $this->goods_module->getSaleGoods($goodsid, 0, 0); 
        if(!count($data)){
            return ;
        }
        $data = $data[0];
        $cache->set($key,$data);                            //设置缓存，增加额外的header
        return $data;
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/goods/best/gid/1?goodsid=12
    function best_get(){
        global $cache,$controller,$method;
        
        $gid = (int)request('gid');
        $goodsid = (int)request('goodsid');
        
        $key = $controller.':'.$method.':'.$gid.':'.$goodsid;
        $cache->get($key);                                  //获取缓存，处理304
        
        $data = $this->goods_module->getBestGoods($gid, $goodsid); 
        $cache->set($key,$data);                            //设置缓存，增加额外的header
        return $data;
    } 
    
}
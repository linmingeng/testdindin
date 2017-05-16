<?php
require_once BASE_PATH.'/modules/store_module.php';
require_once BASE_PATH.'/modules/group_module.php';
require_once BASE_PATH.'/modules/goods_module.php';

/**
 * 应用控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class store{
    
    function __construct(){
    }

    //调用网址：[GET] http://localhost/api/index.php?/app/store/aid/1
    function store_get(){
        global $cache,$controller,$method;

        $aid = (int)request('aid');
        $storeid = (int)request('storeid');
        $key = $controller.':'.$method.':'.$aid.':'.$storeid;
        $cache->get($key);                                  //获取缓存，处理304
        $store_module = new store_module;
        $result['store'] = $store_module->getStore($aid,$storeid);

        $group_module = new group_module;
        $result['store'][0]['groups_detail'] = $group_module->getStoreGroups($result['store'][0]['groupids']);
        $cache->set($key,$result);                                  //获取缓存，处理304
        if(empty($result)){
            return ;
        }
        return $result;
    }
    function group_get(){
        global $cache,$controller,$method;

        $groupid = (int)request('groupid');
        $key = $controller.':'.$method.':'.$groupid;
        $cache->get($key);                                  //获取缓存，处理304

        $group_module = new group_module;
        $result['group_info'] = $group_module->getStoreGroups($groupid);
        $cache->set($key,$result);                                  //获取缓存，处理304
        if(empty($result)){
            return ;
        }
        return $result;
    }
    //调用网址：[GET] http://localhost/api/index.php?/store/goods/aid/3
    function goods_get(){
        global $cache,$controller,$method;

        $aid = (int)request('aid');
        $gid = (int)request('gid');
        $storeid = (int)request('storeid');
        $recommend = (int)request('recommend');
        $page = (int)request('page');
        $page = max($page,1);
        $key = $controller.':'.$method.':'.$aid.':'.$gid.':'.$page.':'.$storeid.':'.$recommend;
        $cache->get($key);                                      //获取缓存，处理304
        $goods_module = new goods_module();
        $result = $goods_module->getStoreGoods($storeid,$recommend,$gid,$page);
        if(empty($result)){
            return ;
        }
        $cache->set($key,$result);                             //设置缓存，增加额外的header
        return $result;
    }

}
<?php
require_once BASE_PATH.'/modules/goods_module.php';
require_once BASE_PATH.'/modules/group_module.php';
require_once BASE_PATH.'/modules/ad_module.php';
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

    function search_get(){

       

        global $cache,$controller,$method;
        $res['param'] = array(
            'sub_groupid'=>(int)request('sub_groupid'),
            'end_groupid'=>(int)request('end_groupid')
        );
        $group_module = new group_module();
        $res['group_crumbs'] = $group_module->groupTree($res['param']['sub_groupid'],$res['param']['end_groupid']);

         //分类信息
        $res['all_groups'] = $this->groups(1);

        return $res;
    }

        //调用网址：[GET] http://localhost/api/index.php?/app/groups/aid/3
    private function groups($aid){
        global $cache,$controller,$method;

//        $aid = (int)request('aid');
        $key = 'pc'.$controller.':'.$method.':'.$aid;
//        $cache->get($key);                                  //获取缓存，处理304

        $group_module = new group_module();
        $result = $group_module->getGroups($aid);
        if(empty($result)){
            return ;
        }
//        $cache->set($key,$result);                          //设置缓存，增加额外的header
        return $result;
    }

    function list_post(){
        $param['sub_groupid'] = (int)request('sub_groupid');
        $param['end_groupid'] = (int)request('end_groupid');
        $page = (int)request('page');
        $data['goods_list'] = $this->goods_module->searchGoods($param, $page);
        return $data;
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
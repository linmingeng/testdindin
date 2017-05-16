<?php
require_once BASE_PATH.'/modules/goods_module.php';
require_once BASE_PATH.'/modules/group_module.php';

class goodsDetail {

    function __construct() {
        //var_dump($this->ad_module);
    }

    function get(){
        $this->search_view();
        return array(1,2,3);
    }
    function search_view(){
        location('index.php?/goodsDetail/view');
    }
    
    function view_get(){
        $goodsid = (int)request('goodsid');
        $goods_module = new goods_module();
        $res['goods_info'] = $goods_module->getOneGoods($goodsid);

         //分类信息
        $res['all_groups'] = $this->groups(1);
        //相关商品
        $res['relevant_goods'] = $this->relevantGoods($res['goods_info']);
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

    function relevantGoods($goodsinfo){
        $groupid = $goodsinfo['groupid'];
        $sub_groupid = $goodsinfo['sub_groupid'];
        if($sub_groupid){
            $goods_module = new goods_module();
            $goods_list = $goods_module->getSomeGoods(1,$groupid,$sub_groupid,6);
            return $goods_list;
        }
        return array();
    }
}
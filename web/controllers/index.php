<?php


require_once BASE_PATH.'/modules/app_conf_module.php';
require_once BASE_PATH.'/modules/goods_module.php';
require_once BASE_PATH.'/modules/group_module.php';
require_once BASE_PATH.'/modules/ad_module.php';
/**
 * 默认控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2013-10-29
 */
class index {

    public $appid = 1;
    function __construct() {

    }

    function get(){

        //店铺配置
        $res['home_ad'] = $this->homeAd($this->appid);
        //首页商品信息
//        $res['home_goods'] = $this->getHomeGoods($this->appid);

        return $res;
    }
    function menu_post(){
        //分类信息
        $res['all_groups'] = $this->groups($this->appid);
        return $res;
    }

    function getHomeGoods(){
        $goods_module = new goods_module();
        $goods = $goods_module->searchGoods('',1,' order by id desc ');
        dre($goods);
    }

    //调用网址：[GET] http://localhost/api/index.php?/app/groups/aid/3
    private function groups($aid){
        global $cache,$controller,$method;

//        $aid = (int)request('aid');
//        $key = 'pc'.$controller.':'.$method.':'.$aid;
//        $cache->get($key);                                  //获取缓存，处理304

        $group_module = new group_module();
        $result = $group_module->getGroups($aid);
        if(empty($result)){
            return ;
        }
//        $cache->set($key,$result);                          //设置缓存，增加额外的header
        return $result;
    }

    private function homeAd($appid){
        global $cache,$controller,$method;
//        $key = $controller.':'.$method.':'.$appid;
//        $cache->get($key);                                  //获取缓存，处理304

        $ad_module = new ad_module();
        $ad_list = $ad_module->getAds($appid,0,0,1);
        foreach($ad_list as &$val){
            for($i=1;$i<6;$i++){
                $detail['image'] = $val['image'.$i];
                $detail['name'] = $val['name'.$i];
                $detail['url'] = $val['url'.$i];
                $str = implode('',$detail);
                if($str != ''){
                    $val['detail'][$i] = $detail;
                }
            }
        }
        if(!is_array($ad_list) ){
            return ;
        }
//        $cache->set($key,$ad_list);                          //设置缓存，增加额外的header
        return $ad_list;
    }


}
<?php
require_once BASE_PATH.'/modules/news_module.php';
/**
 * 新闻控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-07-21
 */
class news {
    
    function __construct(){
        $this->news_module = new news_module();
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/news/list&sid=1&page=1
    function list_get(){
        global $cache,$controller,$method;
        
        $appid = (int)request('appid');
        $page = (int)request('page');
        $page = max($page,1);
        $key = $controller.':'.$method.':'.$appid.':'.$page;
        $cache->get($key);                                  //获取缓存，处理304
        
        $res = $this->news_module->getNews($appid,$page); 
        $cache->set($key,$res);                             //设置缓存，增加额外的header
        return $res;
    } 
    
    //调用网址：[GET] http://localhost/api/index.php?/news/detail/nid/3
    function detail_get(){
        global $cache,$controller,$method;
        
        $newsid = (int)request('newsid');
        $key = $controller.':'.$method.':'.$newsid;
        $cache->get($key);                                  //获取缓存，处理304
        
        $res = $this->news_module->getNewsDetail($newsid);  
        $cache->set($key,$res);                             //设置缓存，增加额外的header
        return $res;
    } 
       
    //调用网址：[GET] http://localhost/api/index.php?/news/best
    function best_get(){
        global $cache,$controller,$method;
        
        $key = $controller.':'.$method.':bestNews';
        $cache->get($key);                                  //获取缓存，处理304
        
        $news = $this->news_module->getBestNews();
        $res = array("news" => $news);
        $cache->set($key,$res);                             //设置缓存，增加额外的header
        return $res;
    }
    
}
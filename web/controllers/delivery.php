<?php
/**
 * 快递控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2015-10-29
 */
class delivery {
    
    function __construct(){
        
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/delivery/detail
    function detail_get(){
        global $cache,$controller,$method;
        
        $company = trim(request('company'));
        $postid = trim(request('postid'));
        
        if(!$company || !$postid){
            return ;
        }
        $key = $controller.':'.$method.':'.$company.':'.$postid;
        $cache->get($key);                                  //获取缓存，处理304
        
        $url = 'http://wap.kuaidi100.com/wap_result.jsp?rand='.date('Ymd').'&id='.$company.'&fromWeb=null&&postid='.$postid;
        
        $econt = file_get_contents($url);
        if($econt){
            $econtArr = explode('<div class="clear"></div>',$econt);
            if(isset($econtArr[2]) && $econtArr[2] ){
                $econtArr = explode('</form>',$econtArr[2]);
                $econt = $econtArr[0];
            }
        }
        $econt = str_replace('<br />',' | ',$econt);
        if(strpos($econt, '请您正确填写快递单号') !== false){
            $econt = '';
        }
        if(strpos($econt, '单号不正确') !== false){
            $econt = '';
        }
        $econt = str_replace('http://wap.kuaidi100.com','',$econt);
        $econt = str_replace('hm.baidu.com','',$econt);
        $data = array('delivery_data' => $econt);
        
        $cache->set($key,$data);                             //设置缓存，增加额外的header
        return $data;
    } 
    
}
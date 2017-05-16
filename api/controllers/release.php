<?php
require_once BASE_PATH.'/modules/release_module.php';
require_once BASE_PATH.'/modules/app_sets_module.php';
/**
 * 打包发布控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2016-07-07
 */
class release {
    
    function __construct(){
        $this->release_module = new release_module();
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/release/status
    function status_get(){
        global $user_info;
        $userid = (int)$user_info['userid'];
        $appid = (int)request('appid');
        $ret = array(
            'h5' => $this->release_module->getReleaseStatus($appid, $userid, 'h5'),
            'android' => $this->release_module->getReleaseStatus($appid, $userid, 'android'),
            //'ios' => $this->release_module->getReleaseStatus($appid, $userid, 'ios'),
        );
        return $ret; 
    } 
    
    //调用网址：[POST] http://localhost/api/index.php?/release/add
    function add_post(){
        global $user_info,$default_app_typeid,$default_app_ver;
        $userid = (int)$user_info['userid'];
        $appid = (int)request('appid');
        $modal = request('modal');
        $modals = array('android');    //todo: 支持ios
        if(!in_array($modal, $modals)){
            return '非法操作';
        }
        $status = $this->release_module->getReleaseStatus($appid, $userid, $modal);
        if($status['status'] == 0 || $status['status'] == 1){
            return '请勿重复提交';
        }
        $app_sets_module = new app_sets_module;
        $typeInfo = $app_sets_module->getTypeInfo($appid,$userid);
        if(isset($typeInfo['typeid'])){
            $typeid = $typeInfo['typeid'];
        }else{
            $typeid = $default_app_typeid;
        }
        if(isset($typeInfo['ver'])){
            $ver = $typeInfo['ver'];
        }else{
            $ver = $default_app_ver;
        }
        $data = array(
            "userid" => $userid,
            "appid" => $appid,
            "typeid" => $typeid,
            "ver" => $ver,
            "modal" => $modal,
            "process" => 0,
            "size" => 0,
            "status" => 0,
            "add_at" => time(),
            "flag" => 1
        );
        $releaseid = $this->release_module->addNow($data);
        return array('releaseid' => $releaseid);
        
    } 
    
}
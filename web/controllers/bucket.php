<?php
require_once BASE_PATH.'/modules/bucket_module.php';
/**
 * 存储桶控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2016-11-24
 */
class bucket {
    
    function __construct(){
        global $user_info;
        $this->userid = $user_info['userid'];
        $this->appid = (int)request('appid');
        $this->bucket_module = new bucket_module();
    }
    
    function check_login(){
        if(!$this->userid){
            error(403,'请重新登录！');
        }
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/bucket/list&page=1
    function list_get(){
        $this->check_login();
        $page = (int)request('page');
        $page = max($page,1);
        return $this->bucket_module->listIt($this->appid, $this->userid, $page); 
    } 
    
    //调用网址：[POST] http://localhost/api/index.php?/bucket/create
    function create_post(){
        $this->check_login();
        /*
        $type = (int)request('type');
        if( ! $type){
            error(406,'请选择云平台！');    
        }
        */
        //只能创建5个存储桶
        if($this->bucket_module->countNums($this->appid, $this->userid) >= 5 ){
            error(406,'对不起，最多只能添加5个存储桶！','alert');    
        }
        $platform = 1;
        $name = trim(request('name'));
        if( ! $name){
            error(406,'请填写存储桶名称！');    
        }
        $accessid = trim(request('accessid'));
        if( ! $name){
            error(406,'请填写阿里云OSS的ACCESS ID');    
        }
        $accesskey = trim(request('accesskey'));
        if( ! $name){
            error(406,'请填写阿里云OSS的ACCESS KEY');    
        }
        
        $endpoint = trim(request('endpoint'));
        if( ! $name){
            error(406,'请填写阿里云OSS的ENDPOINT');    
        }
        
        $bucket = trim(request('bucket'));
        if( ! $name){
            error(406,'请填写阿里云OSS的BUCKET');    
        }
        
        $ret = $this->bucket_module->createIt($this->appid, $this->userid);
        if(is_array($ret) && isset($ret['insertid']) && $ret['insertid'] > 0){
            $ret['alert'] = '存储桶添加成功！';
            return $ret;
        }else{
            error(406,'存储桶添加失败！', 'alert');
        }
    } 
    
    //调用网址：[POST] http://localhost/api/index.php?/bucket/update
    function update_post(){
        $bucketid = (int)request('bucketid');
        $name = trim(request('name'));
        if( ! $name){
            error(406,'请输入存储桶名称！');    
        }
        
        $acl_ret = $this->bucket_module->ACL('update', $bucketid);
        if($acl_ret == -1){
            error(406,'存储桶不存在，更新失败！');
        }else if($acl_ret == 0){
            error(406,'权限不足，更新失败！');
        }
        $ret = $this->bucket_module->updateIt($bucketid);
        if(is_array($ret) && isset($ret['affected_rows']) && $ret['affected_rows'] > 0){
            $ret['alert'] = '更新成功！';
            return $ret;
        }else{
            error(406,'更新失败！', 'alert');
        }
    } 
    
    //调用网址：[GET] http://localhost/api/index.php?/bucket/del/bucketid/1
    function del_get(){
        $bucketid = (int)request('bucketid');
        $acl_ret = $this->bucket_module->ACL('del', $bucketid);
        if($acl_ret == -2){
            error(406,'存储桶正在使用中，无法删除！','alert');
        }else if($acl_ret == -1){
            error(406,'存储桶不存在，删除失败！');
        }else if($acl_ret == 0){
            error(406,'权限不足，删除失败！');
        }
        
        $ret = $this->bucket_module->delIt($bucketid); 
        if(is_array($ret) && isset($ret['affected_rows']) && $ret['affected_rows'] > 0){
            $ret['alert'] = '删除成功！';
            return $ret;
        }else{
            error(406,'删除失败！', 'alert');
        }
    } 
    
    //调用网址：[GET] http://localhost/api/index.php?/bucket/delete/bucketid/1
    function delete_get(){
        $bucketid = (int)request('bucketid');
        $acl_ret = $this->bucket_module->ACL('delete', $bucketid);
        if($acl_ret == -2){
            error(406,'存储桶正在使用中，无法删除！','alert');
        }else if($acl_ret == -1){
            error(406,'存储桶不存在，删除失败！');
        }else if($acl_ret == 0){
            error(406,'权限不足，删除失败！');
        }
        
        $ret = $this->bucket_module->deleteIt($bucketid); 
        if(is_array($ret) && isset($ret['affected_rows']) && $ret['affected_rows'] > 0){
            $ret['alert'] = '删除成功！';
            return $ret;
        }else{
            error(406,'删除失败！', 'alert');
        }
    } 
    
    //调用网址：[GET] http://localhost/api/index.php?/bucket/detail/bucketid/1
    function detail_get(){
        $bucketid = (int)request('bucketid');
        $ret = $this->bucket_module->getIt($bucketid); 
        if(is_array($ret) && count($ret)){
            if($ret['public'] == 0){                //读权限：0=私有读、1=公共读
                $this->check_login();
                if($ret['userid'] != $this->userid){
                    error(406,'权限不足，获取数据失败！');
                }
            }
            return $ret;
        }else{
            error(406,'存储桶不存在，获取数据失败！');
        }
        
    } 
    
}
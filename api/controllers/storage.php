<?php
require_once BASE_PATH.'/modules/bucket_module.php';
require_once BASE_PATH.'/modules/storage_module.php';
/**
 * 轻云盘控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2016-11-24
 */
class storage {
    
    function __construct(){
        global $user_info;
        $this->userid = $user_info['userid'];
        $this->appid = (int)request('appid');
        $this->storage_module = new storage_module();
    }
    
    function check_login(){
        if(!$this->userid){
            error(403,'请重新登录！');
        }
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/storage/list&page=1
    function list_get(){
        $this->check_login();
        $page = (int)request('page');
        $page = max($page,1);
        return $this->storage_module->listIt($this->appid, $this->userid, $page); 
    } 
    
    //调用网址：[POST] http://localhost/api/index.php?/storage/create
    function create_post(){
        $this->check_login();
        
        //只能创建5个存储桶
        if($this->storage_module->countNums($this->appid, $this->userid) >= 5 ){
            error(406,'对不起，最多只能创建5个轻云盘！','alert');    
        }
        $bucketid = (int)request('bucketid');
        if( ! $bucketid){
            error(406,'请选择存储桶！');    
        }
        $name = trim(request('name'));
        if( ! $name){
            error(406,'请输入轻云盘名称！');    
        }
        /*
        $type = (int)request('type');
        if( ! $type){
            error(406,'请选择轻云盘类型！');    
        }
        */
        $_REQUEST['type'] = 1;                          //场景类型：1=个人网盘(私有读、私有写) 2=个人相册(公共读、私有写) 3=个人资源网盘  (公共读、私有写) 
                                                        //          4=组织网盘(公共读/组织读、组织写) 5=组织相册(公共读/组织读、组织写) 6=组织资源网盘(公共读/组织读、组织写) 
        $bucket_module = new bucket_module();
        $ret_bc = $bucket_module->getIt($bucketid,'default_capacity');  //获取bucketid
        if(is_array($ret_bc) && isset($ret_bc['default_capacity']) ){
            $_REQUEST['capacity'] = $ret_bc['default_capacity'];
        }else{
            error(406,'请重新选择一个可用的存储桶！');   
        }
        $ret = $this->storage_module->createIt($this->appid, $this->userid);
        if(is_array($ret) && isset($ret['insertid']) && $ret['insertid'] > 0){
            $ret['alert'] = '创建成功！';
            $bucket_module->incStorages($bucketid);     //计数
            return $ret;
        }else{
            error(406,'创建失败！', 'alert');
        }
    } 
    
    //调用网址：[POST] http://localhost/api/index.php?/storage/update
    function update_post(){
        $storageid = (int)request('storageid');
        $name = trim(request('name'));
        if( ! $name){
            error(406,'请输入轻云盘名称！');    
        }
        /*
        $type = (int)request('type');
        if( ! $type){
            error(406,'请选择轻云盘类型！');
        }
        */
        $_REQUEST['type'] = 1;
        $acl_ret = $this->storage_module->ACL('update', $storageid);
        if($acl_ret == -1){
            error(406,'轻云盘不存在，更新失败！');
        }else if($acl_ret == 0){
            error(406,'权限不足，更新失败！');
        }
        $ret = $this->storage_module->updateIt($storageid);
        if(is_array($ret) && isset($ret['affected_rows']) && $ret['affected_rows'] > 0){
            $ret['alert'] = '更新成功！';
            return $ret;
        }else{
            error(406,'更新失败！', 'alert');
        }
    } 
    
    //调用网址：[GET] http://localhost/api/index.php?/storage/del/storageid/1
    function del_get(){
        $storageid = (int)request('storageid');
        $acl_ret = $this->storage_module->ACL('del', $storageid);
        if($acl_ret == -2){
            error(406,'请先清空轻云盘，再来删除！','alert');
        }else if($acl_ret == -1){
            error(406,'轻云盘不存在，删除失败！');
        }else if($acl_ret == 0){
            error(406,'权限不足，删除失败！');
        }
        
        $ret_bc = $this->storage_module->getIt($storageid,'bucketid');  //获取bucketid
        
        $ret = $this->storage_module->delIt($storageid); 
        if(is_array($ret) && isset($ret['affected_rows']) && $ret['affected_rows'] > 0){
            $ret['alert'] = '删除成功！';
            if(is_array($ret_bc) && isset($ret_bc['bucketid']) && $ret_bc['bucketid']){
                $bucket_module = new bucket_module();
                $bucket_module->decStorages($ret_bc['bucketid']);       //计数
            }
            return $ret;
        }else{
            error(406,'删除失败！', 'alert');
        }
    } 
    
    //调用网址：[GET] http://localhost/api/index.php?/storage/delete/storageid/1
    function delete_get(){
        $storageid = (int)request('storageid');
        $acl_ret = $this->storage_module->ACL('delete', $storageid);
        if($acl_ret == -2){
            error(406,'请先清空轻云盘，再来删除！','alert');
        }else if($acl_ret == -1){
            error(406,'轻云盘不存在，删除失败！');
        }else if($acl_ret == 0){
            error(406,'权限不足，删除失败！');
        }
        
        $ret_bc = $this->storage_module->getIt($storageid,'bucketid');  //获取bucketid
        
        $ret = $this->storage_module->deleteIt($storageid); 
        if(is_array($ret) && isset($ret['affected_rows']) && $ret['affected_rows'] > 0){
            $ret['alert'] = '删除成功！';
            if(is_array($ret_bc) && isset($ret_bc['bucketid']) && $ret_bc['bucketid']){
                $bucket_module = new bucket_module();
                $bucket_module->decStorages($ret_bc['bucketid']);       //计数
            }
            return $ret;
        }else{
            error(406,'删除失败！', 'alert');
        }
    } 
    
    //调用网址：[GET] http://localhost/api/index.php?/storage/detail/storageid/1
    function detail_get(){
        $storageid = (int)request('storageid');
        $ret = $this->storage_module->getIt($storageid); 
        if(is_array($ret) && count($ret)){
            if($ret['public'] == 0){                //读权限：0=私有读、1=公共读
                $this->check_login();
                if($ret['userid'] != $this->userid){
                    error(406,'权限不足，获取数据失败！');
                }
            }
            return $ret;
        }else{
            error(406,'轻云盘不存在，获取数据失败！');
        }
        
    } 
    
}
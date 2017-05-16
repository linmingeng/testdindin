<?php
require_once BASE_PATH.'/modules/storage_module.php';
require_once BASE_PATH.'/modules/folder_module.php';
require_once BASE_PATH.'/modules/file_module.php';
/**
 * 文件控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2016-11-24
 */
class file {
    
    function __construct(){
        global $user_info;
        $this->userid = $user_info['userid'];
        $this->appid = (int)request('appid');
        $this->file_module = new file_module();
    }
    
    function check_login(){
        if(!$this->userid){
            error(403,'请重新登录！');
        }
    }
    
    //调用网址：[POST] http://localhost/api/index.php?/file/create
    function create_post(){
        $this->check_login();
        $storageid = (int)request('storageid');
        $fid = (int)request('fid');
        $name = trim(request('name'));
        if( ! $name){
            error(406,'请输入文件名称！');    
        }
        
        if(!$storageid && !$fid ){
            error(406,'未选择上传目录，文件上传失败！');    
        }
        
        if($storageid){
            $storage_module = new storage_module();
            $acl_ret = $storage_module->ACL('create_sub', $storageid);
            if($acl_ret == -1){
                error(406,'存储空间不存在，文件上传失败！');
            }else if($acl_ret == 0){
                error(406,'权限不足，文件上传失败！');
            }
        }
        
        if($fid){
            $folder_module = new folder_module();
            $acl_ret = $this->folder_module->ACL('create_sub', $fid); 
            if($acl_ret == -1){
                error(406,'父目录不存在，文件上传失败！');
            }else if($acl_ret == 0){
                error(406,'权限不足，文件上传失败！');
            }
        }
        $ret = $this->file_module->createIt($this->appid, $this->userid);
        if(is_array($ret) && isset($ret['insertid']) && $ret['insertid'] > 0){
            $ret['alert'] = '文件上传成功！';
            return $ret;
        }else{
            error(406,'文件上传失败！', 'alert');
        }
    } 
    
    //调用网址：[POST] http://localhost/api/index.php?/file/update
    function update_post(){
        $this->check_login();
        $fileid = (int)request('fileid');
        $name = trim(request('name'));
        if( ! $name){
            error(406,'请输入文件名称！');    
        }
        $acl_ret = $this->file_module->ACL('update', $fileid); 
        if($acl_ret == -1){
            error(406,'文件不存在，更新失败！');
        }else if($acl_ret == 0){
            error(406,'权限不足，更新失败！');
        }
        $ret = $this->file_module->updateIt($fileid);
        if(is_array($ret) && isset($ret['affected_rows']) && $ret['affected_rows'] > 0){
            $ret['alert'] = '更新成功！';
            return $ret;
        }else{
            error(406,'更新失败！', 'alert');
        }
    } 
    
    //调用网址：[GET] http://localhost/api/index.php?/file/del/fileid/1
    function del_get(){
        $this->check_login();
        $fileid = (int)request('fileid');
        $acl_ret = $this->file_module->ACL('del', $fileid); 
        if($acl_ret == -1){
            error(406,'文件不存在，删除失败！');
        }else if($acl_ret == 0){
            error(406,'权限不足，删除失败！');
        }
        $ret = $this->file_module->delIt($fileid); 
        if(is_array($ret) && isset($ret['affected_rows']) && $ret['affected_rows'] > 0){
            return $ret;
        }else{
            error(406,'删除失败！');
        }
    } 
    
    //调用网址：[GET] http://localhost/api/index.php?/file/delete/fileid/1
    function delete_get(){
        $this->check_login();
        $fileid = (int)request('fileid');
        $acl_ret = $this->file_module->ACL('delete', $fileid); 
        if($acl_ret == -1){
            error(406,'文件不存在，删除失败！');
        }else if($acl_ret == 0){
            error(406,'权限不足，删除失败！');
        }
        $ret = $this->file_module->deleteIt($fileid); 
        if(is_array($ret) && isset($ret['affected_rows']) && $ret['affected_rows'] > 0){
            return $ret;
        }else{
            error(406,'删除失败！');
        }
    } 
    
    //调用网址：[POST] http://localhost/api/index.php?/file/detail/fileid/1
    function detail_get(){
        $fileid = (int)request('fileid');
        $ret = $this->file_module->getIt($fileid); 
        if(is_array($ret) && count($ret['userid'])){
            if($ret['public'] == 0){                //读权限：0=私有读、1=公共读
                $this->check_login();
                if($ret['userid'] != $this->userid){
                    error(406,'权限不足，获取数据失败！');
                }
            }
            return $ret;
        }else{
            error(406,'文件不存在，获取数据失败！');
        }
        
    } 
    
}
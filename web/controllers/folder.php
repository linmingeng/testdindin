<?php
require_once BASE_PATH.'/modules/storage_module.php';
require_once BASE_PATH.'/modules/folder_module.php';
require_once BASE_PATH.'/modules/file_module.php';
/**
 * 目录控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2016-11-24
 */
class folder {
    
    function __construct(){
        global $user_info;
        $this->userid = $user_info['userid'];
        $this->appid = (int)request('appid');
        $this->folder_module = new folder_module();
    }
    
    function check_login(){
        if(!$this->userid){
            error(403,'请重新登录！');
        }
    }
    
    //调用网址：[GET] http://localhost/api/index.php?/folder/list&storageid=1&fid=2&page=1&scope=0
    function list_get(){
        $storageid = (int)request('storageid');
        $fid = (int)request('fid');
        $fid = max(0,$fid);
        if($fid == 0){  //一级目录
            $storage_module = new storage_module();
            $acl_ret = $storage_module->ACL('list_sub', $storageid);
            if($acl_ret == -1){
                error(406,'存储空间不存在，获取列表失败！');
            }else if($acl_ret == 0){
                error(406,'权限不足，获取列表失败！');
            }
        }else{          //非一级目录
            $acl_ret = $this->folder_module->ACL('list_sub', $fid);
            if($acl_ret == -1){
                error(406,'目录不存在，获取列表失败！');
            }else if($acl_ret == 0){
                error(406,'权限不足，获取列表失败！');
            }
        }
        
        $mode = (int)request('mode');       //模式：-1=回收站 1=网盘
        $simple = (int)request('simple');       //1=返回最小数据
        $page = (int)request('page');
        $order_field = strtolower(trim(request('order_field')));
        $order_by = strtoupper(trim(request('order_by')));
        $page = max($page,1);
        
        $scope = (int)request('scope');         //scope: 0=目录+文件 1=文件 2=目录
        $data = array('next_scope' => $scope,'previous_scope' => $scope);
        
        $file_module = new file_module();
        if($scope == 2){
            $data['folders'] = $this->folder_module->listIt($storageid, $fid, $order_field, $order_by, $page, $mode, $simple); 
            $data['next_page'] = $data['folders']['next'];
            $data['previous_page'] = $data['folders']['previous'];
        }else if($scope == 1){
            $data['files'] = $file_module->listIt($storageid, $fid, $order_field, $order_by, $page, $mode, $simple); 
            $data['next_page'] = $data['files']['next'];
            $data['previous_page'] = $data['files']['previous'];
        }else{
            $data['folders'] = $this->folder_module->listIt($storageid, $fid, $order_field, $order_by, $page, $mode, $simple); 
            $page_size = $this->folder_module->pageSize;
            if($data['folders']['count'] < $page_size*$page ){         //当前页为目录+文件
                $data['files'] = $file_module->listIt($storageid, $fid, $order_field, $order_by, 1, $mode, $simple);    //获取第一页文件
                $data['next_scope'] = 1;                               //下一页读取文件列表
                $data['previous_scope'] = 0;                           //上一页读取目录+文件列表
                $data['next_page'] = $data['files']['next'];
                $data['previous_page'] = $data['folders']['previous'];
            }else if($data['folders']['count'] >= $page_size*$page ){  //当前页为目录
                $data['next_scope'] = 0;                               //下一页读取目录+文件列表
                $data['previous_scope'] = 0;                           //上一页读取目录+文件列表
                $data['next_page'] = $data['folders']['next'];
                $data['previous_page'] = $data['folders']['previous'];
            }
        }
        return $data; 
    } 
    
    //调用网址：[POST] http://localhost/api/index.php?/folder/create
    function create_post(){
        $this->check_login();
        $storageid = (int)request('storageid');
        if( ! $storageid){
            error(406,'请选择存储空间！');    
        }
        $name = trim(request('name'));
        if( ! $name){
            error(406,'请输入目录名称！');    
        }
        $storage_module = new storage_module();
        $acl_ret = $storage_module->ACL('create_sub', $storageid);
        if($acl_ret == -1){
            error(406,'存储空间不存在，创建目录失败！');
        }else if($acl_ret == 0){
            error(406,'权限不足，创建目录失败！');
        }
        
        $fid = (int)request('fid');
        if($fid){
            $acl_ret = $this->folder_module->ACL('create_sub', $fid); 
            if($acl_ret == -1){
                error(406,'父目录不存在，子目录创建失败！');
            }else if($acl_ret == 0){
                error(406,'权限不足，子目录创建失败！');
            }
        }
        $ret = $this->folder_module->createIt($this->appid, $this->userid);
        if(is_array($ret) && isset($ret['insertid']) && $ret['insertid'] > 0){
            $ret['tip'] = '创建成功！';
            return $ret;
        }else{
            error(406,'创建失败！', 'alert');
        }
    } 
    
    //调用网址：[POST] http://localhost/api/index.php?/folder/update
    function update_post(){
        $this->check_login();
        $folderid = (int)request('folderid');
        $name = trim(request('name'));
        if( ! $name){
            error(406,'请输入目录名称！');
        }
        $acl_ret = $this->folder_module->ACL('update', $folderid); 
        if($acl_ret == -1){
            error(406,'目录不存在，更新失败！');
        }else if($acl_ret == 0){
            error(406,'权限不足，更新失败！');
        }
        $ret = $this->folder_module->updateIt($folderid);
        if(is_array($ret) && isset($ret['affected_rows']) && $ret['affected_rows'] > 0){
            $ret['alert'] = '更新成功！';
            return $ret;
        }else{
            error(406,'更新失败！', 'alert');
        }
    } 
    
    //调用网址：[POST] http://localhost/api/index.php?/folder/del/folderid/1
    function del_get(){
        $this->check_login();
        $folderid = (int)request('folderid');
        $acl_ret = $this->folder_module->ACL('del', $folderid); 
        if($acl_ret == -1){
            error(406,'目录不存在，删除失败！');
        }else if($acl_ret == 0){
            error(406,'权限不足，删除失败！');
        }
        $ret = $this->folder_module->delIt($folderid); 
        if(is_array($ret) && isset($ret['affected_rows']) && $ret['affected_rows'] > 0){
            //todo: 写入异步删除队列
            return $ret;
        }else{
            error(406,'删除失败！');
        }
    } 
    
    //调用网址：[POST] http://localhost/api/index.php?/folder/delete/folderid/1
    function delete_get(){
        $this->check_login();
        $folderid = (int)request('folderid');
        $acl_ret = $this->folder_module->ACL('delete', $folderid); 
        if($acl_ret == -1){
            error(406,'目录不存在，删除失败！');
        }else if($acl_ret == 0){
            error(406,'权限不足，删除失败！');
        }
        $ret = $this->folder_module->deleteIt($folderid); 
        if(is_array($ret) && isset($ret['affected_rows']) && $ret['affected_rows'] > 0){
            //todo: 写入异步彻底删除队列
            return $ret;
        }else{
            error(406,'删除失败！');
        }
    } 
    
    //调用网址：[POST] http://localhost/api/index.php?/folder/detail/folderid/1
    function detail_get(){
        $folderid = (int)request('folderid');
        $ret = $this->folder_module->getIt($folderid); 
        if(is_array($ret) && count($ret['userid'])){
            if($ret['public'] == 0){                //读权限：0=私有读、1=公共读
                $this->check_login();
                if($ret['userid'] != $this->userid){
                    error(406,'权限不足，获取数据失败！');
                }
            }
            return $ret;
        }else{
            error(406,'目录不存在，获取数据失败！');
        }
        
    } 
    
}
<?php
/**
 * 存储空间模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2016-11-24
 */
class storage_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $fieldsArr = array(
            "`storageid`",
            "`appid`", 
            "`bucketid`", 
            "`userid`",
            "`type`",
            "`name`",
            "`info`", 
            "`folders`",
            "`files`",
            "`capacity`",
            "`used_size`", 
            "`header`",
            "`attr`",
            "`public`",
            "`status`",
            "`add_at`",
            "`update_at`", 
        );
        
       $this->fields = implode(",",$fieldsArr);
       
       $page_size = (int)request('page_size');
       $page_size = max(10, $page_size);
       $page_size = min(1000, $page_size);
       
       $this->pageSize = $page_size;
    }
    
    //当前用户权限判断
    //action: update delete del create_sub list_sub
    function ACL($action, $storageid = 0 ){
        global $user_info;
        $userid = $user_info['userid'];
        
        if($action == 'update' || $action == 'create_sub' ){
            $ret = $this->getIt($storageid, array('userid')); 
            if(is_array($ret) && count($ret)){
                if($ret['userid'] == $userid){              //私有更新、创建子目录
                    return 1;       //有权限
                }else{
                    return 0;       //没权限
                }
            }else{
                return -1;          //存储空间不存在
            }
        }else if($action == 'delete' || $action == 'del' ){
            $ret = $this->getIt($storageid, array('userid','folders','files')); 
            if(is_array($ret) && count($ret)){
                if($ret['userid'] == $userid){              //私有删除
                    if($ret['folders'] > 0 || $ret['files'] > 0 ){
                        return -2;  //存储空间不为空，无法删除
                    }
                    return 1;       //有权限
                }else{
                    return 0;       //没权限
                }
            }else{
                return -1;          //存储空间不存在
            }
        }else if($action == 'list_sub' ){
            $ret = $this->getIt($storageid, array('userid','public')); 
            if(is_array($ret) && count($ret)){
                if($ret['public'] == 0){                    //读权限：0=私有读、1=公共读
                    if($ret['userid'] == $userid){          //私有更新、删除、创建子目录
                        return 1;   //有权限
                    }else{
                        return 0;   //没权限
                    }
                }else{
                    return 1;       //公共读
                }
            }else{
                return -1;          //存储空间不存在
            }
        }
        return -3;                  //未知权限
    }

    //获取列表
    function listIt($appid, $userid, $page = 1){
        $page = max($page,1);
        $data = array(
            "count" => 0,
            "next" => "",
            "previous" => "",
            "results" => "",
        );
        $whereArr = array();
        array_push($whereArr, "`appid` = '".$appid."'");
        array_push($whereArr, "`userid` = '".$userid."'");
        array_push($whereArr, "`status` = 1");
        $where = implode(" AND ",$whereArr);
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_storage` WHERE ".$where." ";    //计算总数
        $countRes = $this->db->getX($countSql);
        if(is_array($countRes)){
            $data["count"] = $countRes[0]["count"];
        }
        $page = min($page,ceil($data["count"]/$this->pageSize));
        if($data["count"]){
            if($data["count"] > $this->pageSize*$page){
                $data["next"] = $page+1;
            }
            if($page > 1){
                $data["previous"] = $page-1;
            }
            $limit = $this->pageSize*($page-1).",".$this->pageSize;
            $sql = "SELECT ".$this->fields." FROM `the_storage` WHERE ".$where." ORDER BY `storageid` DESC LIMIT ".$limit;
            $results = $this->db->getX($sql);
            $data["results"] = $results;
        }
        return $data;
    }
    
    //添加
    function createIt($appid, $userid){
        $bucketid = (int)request('bucketid');
        $name = trim(request('name'));
        $type = (int)request('type');
        $info = trim(request('info'));
        $header = trim(request('header'));
        $attr = trim(request('attr'));
        $public = (int)request('public');
        $capacity = (int)request('capacity');

        $data = array(
            "appid" => $appid,
            "bucketid" => $bucketid,
            "userid" => $userid,
            "type" => $type,
            "name" => $name,
            "info" => $info,
            "folders" => 0,
            "files" => 0,
            "capacity" => $capacity,
            "used_size" => 0,
            "header" => $header,
            "attr" => $attr,
            "public" => $public,
            "status" => 1,
            "add_at" => time(),
            "update_at" => time()
        );

        $insertid = $this->db->add('the_storage',$data);   //写入记录
        return array('insertid' => $insertid);
    }
    
    //修改
    function updateIt($storageid){
        $name = trim(request('name'));
        $type = (int)request('type');
        $info = trim(request('info'));
        $header = trim(request('header'));
        $attr = trim(request('attr'));
        $public = (int)request('public');

        $update = array(
            "type" => $type,
            "name" => $name,
            "info" => $info,
            "header" => $header,
            "attr" => $attr,
            "public" => $public,
            "update_at" => time()
        );

        $data = array($storageid, $update);

        $affected_rows = $this->db->update('the_storage',$data);   //写入记录
        return array('affected_rows' => $affected_rows);
    }
    
    //逻辑删除
    function delIt($storageid){
        $affected_rows = $this->db->updateX("UPDATE `the_storage` SET `status` = -1, `update_at` = '".time()."' WHERE `storageid` = '".$storageid."' ");
        return array('affected_rows' => $affected_rows);
    }
    
    //彻底删除
    function deleteIt($storageid){
        $affected_rows = $this->db->delX("DELETE FROM `the_storage` WHERE `storageid` = '".$storageid."' ");
        return array('affected_rows' => $affected_rows);
    }
    
    //读取
    function getIt($storageid, $fields_array){
        $fields = $this->fields;
        if($fields_array){
            if(!is_array($fields_array)){
                $fields_array = array($fields_array);
            }
            if(count($fields_array)){
                $fields = implode(",",$fields_array);
            }
        }
        $data = array();
        $sql = "SELECT ".$fields." FROM `the_storage` WHERE `storageid` = '".$storageid."' ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
        }
        return $data;
    }
    
    //统计
    function countNums($appid, $userid){
        $ret = $this->db->getX("SELECT COUNT(*) AS `num` FROM `the_storage` WHERE `appid` = '".$appid."' AND `userid` = '".$userid."' " );
        if(count($ret)){
            return $ret[0]['num'];
        }
    }
    
    //文件数+1，已用空间统计
    function incFileSize($storageid, $size){
        $affected_rows = $this->db->updateX("UPDATE `the_storage` SET `used_size` = `used_size` +'".$size."', `files` = `files` +1, `update_at` = '".time()."' WHERE `storageid` = '".$storageid."' ");
        return array('affected_rows' => $affected_rows);
    }
    
    //文件数-1，已用空间统计
    function deccFileSize($storageid, $size){
        $affected_rows = $this->db->updateX("UPDATE `the_storage` SET `used_size` = `used_size` -'".$size."', `files` = `files` -1, `update_at` = '".time()."' WHERE `storageid` = '".$storageid."' ");
        return array('affected_rows' => $affected_rows);
    }
    
    //目录+1
    function incFolders($storageid, $size){
        $affected_rows = $this->db->updateX("UPDATE `the_storage` SET `folders` = `folders` +1, `update_at` = '".time()."' WHERE `storageid` = '".$storageid."' ");
        return array('affected_rows' => $affected_rows);
    }
    
    //目录-1
    function decFolders($storageid, $size){
        $affected_rows = $this->db->updateX("UPDATE `the_storage` SET `folders` = `folders` -1, `update_at` = '".time()."' WHERE `storageid` = '".$storageid."' ");
        return array('affected_rows' => $affected_rows);
    }
}
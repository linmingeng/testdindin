<?php
/**
 * 存储桶模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2016-11-24
 */
class bucket_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $fieldsArr = array(
            "`bucketid`",
            "`appid`",
            "`userid`",
            "`name`",
            "`info`", 
            "`public`",
            "`platform`",
            "`tag`",
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
    //action: update delete del create_storage list_storage
    function ACL($action, $bucketid = 0 ){
        global $user_info;
        $userid = $user_info['userid'];
        
        if($action == 'update' ){
            $ret = $this->getIt($bucketid, array('userid')); 
            if(is_array($ret) && count($ret)){
                if($ret['userid'] == $userid){              //私有更新
                    return 1;       //有权限
                }else{
                    return 0;       //没权限
                }
            }else{
                return -1;          //存储桶不存在
            }
        }else if($action == 'delete' || $action == 'del' ){
            $ret = $this->getIt($bucketid, array('userid','storages')); 
            if(is_array($ret) && count($ret)){
                if($ret['userid'] == $userid){              //私有删除
                    if($ret['storages'] > 0){
                        return -2;                          //当前存储桶正在使用中
                    }
                    return 1;       //有权限
                }else{
                    return 0;       //没权限
                }
            }else{
                return -1;          //存储桶不存在
            }
        }else if($action == 'create_storage' || $action == 'list_storage'){
            $ret = $this->getIt($bucketid, array('userid','public')); 
            if(is_array($ret) && count($ret)){
                if($ret['public'] == 0){                    //读权限：0=私有读、1=公共读
                    if($ret['userid'] == $userid){          //私有更新、删除、创建存储空间
                        return 1;   //有权限
                    }else{
                        return 0;   //没权限
                    }
                }else{
                    return 1;       //公共读
                }
            }else{
                return -1;          //存储桶不存在
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
        //array_push($whereArr, "`status` = 1");
        $where = implode(" AND ",$whereArr);
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_bucket` WHERE ".$where." ";    //计算总数
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
            $sql = "SELECT ".$this->fields." FROM `the_bucket` WHERE ".$where." ORDER BY `bucketid` DESC LIMIT ".$limit;
            $results = $this->db->getX($sql);
            $data["results"] = $results;
        }
        return $data;
    }

    //添加
    function createIt($appid, $userid){
        $platform = (int)request('platform');
        $name = trim(request('name'));
        $info = trim(request('info'));
        $public = (int)request('public');
        $accessid = trim(request('accessid'));
        $accesskey = trim(request('accesskey'));
        $endpoint = trim(request('endpoint'));
        $bucket = trim(request('bucket'));
        $conf = array(
            'accessid' => $accessid,
            'accesskey' => $accesskey,
            'endpoint' => $endpoint,
            'bucket' => $bucket,
        );
        $tag = $bucket.'.'.$endpoint;
        $data = array(
            "appid" => $appid,
            "userid" => $userid,
            "platform" => $platform,
            "name" => $name,
            "info" => $info,
            "public" => $public,
            "conf" => myjson_encode($conf),
            "tag" => $tag,
            "status" => 1,
            "add_at" => time(),
            "update_at" => time()
        );

        $insertid = $this->db->add('the_bucket',$data);   //写入记录
        return array('insertid' => $insertid);
    }
    
    //修改
    function updateIt($bucketid){
        $name = trim(request('name'));
        $info = trim(request('info'));
        $public = (int)request('public');

        $update = array(
            "name" => $name,
            "info" => $info,
            "public" => $public,
            "update_at" => time()
        );

        $data = array($bucketid, $update);

        $affected_rows = $this->db->update('the_bucket',$data);   //写入记录
        return array('affected_rows' => $affected_rows);
    }
    
    //逻辑删除
    function delIt($bucketid){
        $affected_rows = $this->db->updateX("UPDATE `the_bucket` SET `status` = -1, `update_at` = '".time()."' WHERE `bucketid` = '".$bucketid."' ");
        return array('affected_rows' => $affected_rows);
    }
    
    //彻底删除
    function deleteIt($bucketid){
        $affected_rows = $this->db->delX("DELETE FROM `the_bucket` WHERE `bucketid` = '".$bucketid."' ");
        return array('affected_rows' => $affected_rows);
    }
    
    //读取
    function getIt($bucketid, $fields_array){
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
        $sql = "SELECT ".$fields." FROM `the_bucket` WHERE `bucketid` = '".$bucketid."' ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
        }
        return $data;
    }
    
    //统计
    function countNums($appid, $userid){
        $ret = $this->db->getX("SELECT COUNT(*) AS `num` FROM `the_bucket` WHERE `appid` = '".$appid."' AND `userid` = '".$userid."' " );
        if(count($ret)){
            return $ret[0]['num'];
        }
    }
    
    //轻网盘+1
    function incStorages($bucketid){
        $affected_rows = $this->db->updateX("UPDATE `the_bucket` SET `storages` = `storages` +1, `update_at` = '".time()."' WHERE `bucketid` = '".$bucketid."' ");
        return array('affected_rows' => $affected_rows);
    }
    
    //轻网盘-1
    function decStorages($bucketid){
        $affected_rows = $this->db->updateX("UPDATE `the_bucket` SET `storages` = `storages` -1, `update_at` = '".time()."' WHERE `bucketid` = '".$bucketid."' ");
        return array('affected_rows' => $affected_rows);
    }
}
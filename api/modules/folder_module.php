<?php
/**
 * 目录模型
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2016-11-24
 */
class folder_module {
    
    function __construct() {
       global $db;
       $this->db = $db;
       
       $fieldsArr = array(
            "`folderid`",
            "`appid`", 
            "`storageid`", 
            "`fid`",
            "`userid`",
            "`name`",
            "`info`", 
            "`folders`",
            "`files`",
            "`size`",
            "`header`",
            "`attr`",
            "`public`",
            "`status`",
            "`add_at`",
            "`update_at`",
        );
        
       $this->fields = implode(",",$fieldsArr);
       
       $page_size = (int)request('page_size');
       $page_size = max(100, $page_size);
       $page_size = min(1000, $page_size);
       
       $this->pageSize = $page_size;
    }
    
    //当前用户权限判断
    //action:  update del delete create_sub list_sub
    function ACL($action, $folderid = 0){
        global $user_info;
        $userid = $user_info['userid'];
        
        if($action == 'update' || $action == 'del' || $action == 'delete' || $action == 'create_sub' ){
            $ret = $this->getIt($folderid, array('userid')); 
            if(is_array($ret) && count($ret)){
                if($ret['userid'] == $userid){              //私有更新、删除、创建子目录
                    return 1;       //有权限
                }else{
                    return 0;       //没权限
                }
            }else{
                return -1;          //存储空间不存在
            }
        }else if($action == 'list_sub' ){
            $ret = $this->getIt($folderid, array('userid','public')); 
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
        return -2;                  //未知权限
    }
    
    //获取列表
    function listIt($storageid, $fid, $order_field, $order_by, $page = 1, $mode = 1, $simple = false){
        $page = max($page,1);
        $data = array(
            "count" => 0,
            "next" => "",
            "previous" => "",
            "results" => "",
        );
        $whereArr = array();
        array_push($whereArr, "`storageid` = '".$storageid."'");
        if($mode == -1){                                //回收站模式 mode：-1=回收站 1=云盘
            array_push($whereArr, "`status` = -1");     //回收站仅显示直接删除的 status：-2=间接删除 -1=直接删除 1=正常
        }else{
            array_push($whereArr, "`fid` = '".$fid."'");
            array_push($whereArr, "`status` = 1");
        }
        $where = implode(" AND ",$whereArr);
        
        if($mode == -1){
            $order_field = 'delete_at';
            $order_by = 'DESC';
        }else{
            if($order_field != 'add_at' && $order_field != 'name' ){
                $order_field = 'add_at';
            }
            if($order_by != 'DESC' && $order_by != 'ASC' ){
                $order_by = 'ASC';
            }
        }
        $countSql = "SELECT COUNT(*) AS `count` FROM `the_folder` WHERE ".$where." ";    //计算总数
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
            if($simple){
                $fields = '`folderid`,`fid`,`name`,`size`,`add_at`';
            }else{
                $fields = $this->fields;
            }
            $sql = "SELECT ".$fields." FROM `the_folder` WHERE ".$where." ORDER BY `".$order_field."` ".$order_by." LIMIT ".$limit;
            $results = $this->db->getX($sql);
            $data["results"] = $results;
        }
        return $data;
    }
    
    //添加
    function createIt($appid, $userid){
        $storageid = (int)request('storageid');
        $fid = (int)request('fid');
        $name = trim(request('name'));
        $info = trim(request('info'));
        $header = trim(request('header'));
        $attr = trim(request('attr'));
        $public = (int)request('public');
        $status = 1;    //模式：status：-2=间接删除 -1=直接删除 1=正常
        
        //todo：重名判断
        
        $data = array(
            "appid" => $appid,
            "storageid" => $storageid,
            "fid" => $fid,
            "userid" => $userid,
            "name" => $name,
            "info" => $info,
            "folders" => 0,
            "files" => 0,
            "size" => 0,
            "header" => $header,
            "attr" => $attr,
            "public" => $public,
            "status" => $status,
            "add_at" => time(),
            "update_at" => time()
        );

        $insertid = $this->db->add('the_folder',$data);
        return array('insertid' => $insertid);
    }
    
    //修改
    function updateIt($folderid){
        $name = trim(request('name'));
        $info = trim(request('info'));
        $header = trim(request('header'));
        $attr = trim(request('attr'));
        $public = (int)request('public');

        $update = array(
            "name" => $name,
            "info" => $info,
            "header" => $header,
            "attr" => $attr,
            "public" => $public,
            "update_at" => time()
        );

        $data = array($folderid, $update);

        $affected_rows = $this->db->update('the_folder',$data);
        return array('affected_rows' => $affected_rows);
    }
    
    //移动
    function moveIt($folderid, $fid){
        $update = array(
            "fid" => $fid,
            "update_at" => time()
        );
        $data = array($folderid, $update);
        $affected_rows = $this->db->update('the_folder',$data);
        return array('affected_rows' => $affected_rows);
    }
    
    //逻辑删除
    function delIt($folderid){
        $affected_rows = $this->db->delX("UPDATE `the_folder` SET `status`= -1,`delete_at` = ".time()." WHERE `folderid` = '".$folderid."' ");
        return array('affected_rows' => $affected_rows);
    }
    
    //彻底删除
    function deleteIt($folderid){
        $affected_rows = $this->db->delX("DELETE FROM `the_folder` WHERE `folderid` = '".$folderid."' ");
        return array('affected_rows' => $affected_rows);
    }
    
    //读取
    function getIt($folderid, $fields_array){
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
        $sql = "SELECT ".$fields." FROM `the_folder` WHERE `folderid` = '".$folderid."' ";
        $ret = $this->db->getX($sql);
        if(count($ret)){
            $data = $ret[0];
        }
        return $data;
    }
    
}
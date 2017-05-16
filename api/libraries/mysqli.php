<?php
/**
 * 数据库操作 
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2013-10-29
 */

!defined('IN_FUNREST') && exit('Access Denied');

class mysql {

    var $version = '';
    var $querynum = 0;
    var $link;
    var $hostname;
    var $username;
    var $passwd;
    var $dbname;
    var $port;
    var $dbcharset;
    
    function connect($hostname, $username, $passwd, $dbname = 'test', $port = 3306, $dbcharset = 'utf8') {
        $this->hostname = $hostname;
        $this->username = $username;
        $this->passwd = $passwd;
        $this->dbname = $dbname;
        $this->port = $port;
        $this->dbcharset = $dbcharset;
        
        $this->link = mysqli_connect($hostname, $username, $passwd, $dbname, $port);
        if( mysqli_connect_errno() ) {
            $this->halt('Error:'.mysqli_connect_error().';hostname:'.$this->hostname.';username:'.$this->username.';passwd:'.$this->passwd.';dbname:'.$this->dbname.';port:'.$this->port.';dbcharset:'.$this->dbcharset.';');
        } else {
            if($dbcharset){
                $dbcharset = strtolower($dbcharset);
            }
            if( ! in_array($dbcharset,array('utf8','gbk','big5','gb2312','binary'))){
                $dbcharset = 'utf8';
            }
            mysqli_set_charset($this->link,$dbcharset);
        }   
    }
    
    function reconnect(){
        $this->close();
        $hostname = $this->hostname;
        $username = $this->username;
        $passwd = $this->passwd;
        $dbname = $this->dbname;
        $port = $this->port;
        $dbcharset = $this->dbcharset;
        $this->connect($hostname, $username, $passwd, $dbname, $port, $dbcharset);
    }
    
    function select_db($dbname) {
        return mysqli_select_db($this->link, $dbname);
    }
    
    function fetch_array($query, $result_type = MYSQLI_ASSOC) {
        return mysqli_fetch_array($query, $result_type);
    }
    
    function fetch_first($sql) {
        return $this->fetch_array($this->query($sql));
    }
    
    function query($sql, $type = '') {
        if( ! $this->ping()){
            $this->reconnect();
        }
        if(!($query = mysqli_query($this->link, $sql))) {
            if(in_array($this->errno(), array(2006, 2013)) && substr($type, 0, 5) != 'RETRY') {
                $this->reconnect();
                $this->query($sql, 'RETRY'.$type);
            } elseif($type != 'SILENT' && substr($type, 5) != 'SILENT') {
                $this->halt('Errno:'.$this->errno().';Error:'.$this->error().';Sql:'.$sql);
            }
        }

        $this->querynum++;
        return $query;
    }
    
    function affected_rows() {
        return mysqli_affected_rows($this->link);
    }
    
    function error() {
        return (($this->link) ? mysqli_error($this->link) : mysqli_error());
    }
    
    function errno() {
        return intval(($this->link) ? mysqli_errno($this->link) : mysqli_errno());
    }
    
    function num_rows($query) {
        $query = mysqli_num_rows($query);
        return $query;
    }
    
    function num_fields($query) {
        return mysqli_num_fields($query);
    }
    
    function free_result($query) {
        return mysqli_free_result($query);
    }
     
    function insert_id() {
        return mysqli_insert_id($this->link);
    }
    
    function fetch_row($query) {
        $query = mysqli_fetch_row($query);
        return $query;
    }
    
    function fetch_fields($query) {
        return mysqli_fetch_field($query);
    }
    
    function version() {
        if(empty($this->version)) {
            $this->version = mysqli_get_server_info($this->link);
        }
        return $this->version;
    }
    
    function close() {
        return mysqli_close($this->link);
    }
    
    function ping() {
        return mysqli_ping($this->link);
    }
    
    function halt($message = '') {
        if(empty($message)){
            return ;
        }
        $this->close();
        throw new Exception($message);
    }
    
    /**
     *描述: 获取当前数据库的全部表名
     *返回：一维数组
     *示例：$results = $db->getTables();
    */
    function getTables() {
        $query = $this->query("SHOW TABLES");
        while($res = $this->fetch_array($query)) {
            if(is_array($res)){
                $res = array_values($res);    
                $results[] = $res[0];
            }
        }
        $this->free_result($query);
        return $results;
    }
    
    /**
     *描述: 获取当前表的全部字段名
     *返回：一维数组
     *示例：$table = "the_article";
            $results = $db->getFields($table);
    */
    function getFields($table) {
        $query = $this->query("SELECT * FROM `".$table."` LIMIT 1 ");
        $num = $this->num_fields($query);
        $i = 0;
        while($i < $num) {
            $meta = $this->fetch_fields($query);
            if ($meta) {
                $results[] = $meta->name;
            }
            $i++;
        }
        $this->free_result($query);
        return $results;
    }
    
    /**
     *描述: 获取当前表的主键名
     *返回：主键名 或 空
     *示例：$table = "the_article";
            $results = $db->getKeyField($table);
    */
    function getKeyField($table) {
        $query = $this->query("SELECT * FROM `".$table."` LIMIT 1 ");
        $meta = $this->fetch_fields($query);
        if ($meta) {
            $result = $meta->name;
        }else{
            $result = "";
        }
        $this->free_result($query);
        return $result;
    }
    
    /**
     *描述：新增记录
     *参数: $table => 表名,$data => 一维数组
     *返回: 0=>失败,1=>成功
     *示例：$table = "the_article";
            $data = array('title' => 'my title 1','content' => 'my content 1');
            $results = $db->add($table,$data);
    */
    function add($table,$data) {
        if(!is_array($data)){
            return 0;
        }
        
        foreach($data as $key => $val){
            $fields[] = "`".$key."`";
            $value[] = "'".$val."'";
        }
        
        $this->query("INSERT INTO `".$table."` (".implode($fields,",").")VALUES(".implode($value,",").")");
        $insertId = $this->insert_id();
        return $insertId;
    }
    
    /**
     *描述: 新增记录(高级)
     *返回：0=>失败,1=>成功
     *示例：$sql = "INSERT INTO `the_article` (`title`,`content`)VALUE('my title x','my content x')";
            $results = $db->addX($sql);
    */
    function addX($sql) {
        $this->query($sql);
        $insertId = $this->insert_id();
        return $insertId;
    }
    
    /**
     *描述：删除一条/多条记录
     *参数: $table => 表名,$idArray => 主键值(整数/一维数组)
     *返回: 0=>失败,大于0=>成功
     *示例：$table = "the_article";
            $idArray = 1;
            $results = $db->del($table,$idArray);
            
     *示例：$table = "the_article";
            $idArray = array(1,2,3);
            $results = $db->del($table,$idArray);
    */
    function del($table,$idArray) {
        if(is_array($idArray)){
            $idStr = " IN (".implode($idArray,",").") ";
        }else{
            $idStr = " = '".$idArray."' ";
        }
        
        $keyField = $this->getKeyField($table);
        if($keyField == ""){
            return 0;    
        }
        
        $this->query("DELETE FROM `".$table."` WHERE `".$keyField."` ".$idStr." ");
        $affectedRows = $this->affected_rows();
        if($affectedRows < 1){
            return 0;
        }
        return $affectedRows;
    }
    
    /**
     *描述: 删除记录(高级)
     *返回：0=>失败,1=>成功
     *示例：$sql = "DELETE FROM `the_article` WHERE `aid` = 1 ";
            $results = $db->delX($sql);
    */
    function delX($sql) {
        $this->query($sql);
        $affectedRows = $this->affected_rows();
        if($affectedRows < 1){
            return 0;
        }
        return $affectedRows;
    }
    
    /**
     *描述：更新一条/多条记录
     *参数: $table => 表名,$dataArray => 多维数组
     *返回: 0=>失败,1=>成功
     *示例：$table = "the_article";
            $dataArray = array(array(1,2,3,4),array('title' => 'my title 1234','content' => 'my content'));
            $results = $db->update($table,$dataArray);
            
     *示例：$table = "the_article";
            $dataArray = array(5,array('title' => 'my title 5','content' => 'my content'));
            $results = $db->update($table,$dataArray);
    */
    function update($table,$dataArray) {
        if(!is_array($dataArray)){
            return 0;
        }
        
        if(!isset($dataArray[0]) || !isset($dataArray[1]) ){
            return 0;
        }
        
        if(!is_array($dataArray[1])){
            return 0;
        }
        
        if(is_array($dataArray[0])){
            $idStr = " IN (".implode($dataArray[0],",").") ";
        }else{
            $idStr = " = ".$dataArray[0]." ";
        }
        
        $keyField = $this->getKeyField($table);
        if($keyField == ""){
            return 0;    
        }
        
        foreach($dataArray[1] as $key => $val){
            $sqlTempArray[] = " `".$key."` = '".$val."' "; 
        }
        
        $this->query("UPDATE `".$table."` SET ".implode($sqlTempArray,",")." WHERE `".$keyField."` ".$idStr." ");
        
        $affectedRows = $this->affected_rows();
        if($affectedRows < 1){
            return 0;
        }
        
        return $affectedRows;
    }
    
    /**
     *描述: 更新记录(高级)
     *返回：0=>失败,1=>成功
     *示例：$sql = "UPDATE `the_article` SET `title` = 'new title / update' WHERE `aid` = 1 ";
            $results = $db->delX($sql);
    */
    function updateX($sql) {
        $this->query($sql);
        $affectedRows = $this->affected_rows();
        if($affectedRows < 1){
            return 0;
        }
        return $affectedRows;
    }
    
    /**
     *描述: 获取一条/多条记录
     *参数: $table => 表名,$idArray => 主键值(整数/一维数组)
     *返回：多维数组
     *示例：$table = "the_article";
            $idArray = 1;
            $results = $db->get($table,$idArray);
            
     *示例：$table = "the_article";
            $idArray = array(1,2,3);
            $results = $db->get($table,$idArray);
    */
    function get($table,$idArray) {
        if(is_array($idArray)){
            $idStr = " IN (".implode($idArray,",").") ";
        }else{
            $idStr = " = '".$idArray."' ";
        }
        
        $keyField = $this->getKeyField($table);
        if($keyField == ""){
            return 0;    
        }
        
        $query = $this->query("SELECT * FROM `".$table."` WHERE `".$keyField."` ".$idStr." ");
        while($res = $this->fetch_array($query)) {
            $results[] = $res;
        }
        $this->free_result($query);
        return $results;
    }
    
    /**
     *描述: 获取记录(高级)
     *返回：多维数组
     *示例：$sql = "SELECT * FROM `the_article`";
            $results = $db->getX($sql);
    */
    function getX($sql) {
        $results = array();
        $query = $this->query($sql);
        while($res = $this->fetch_array($query)) {
            $results[] = $res;
        }
        $this->free_result($query);
        return $results;
    }
    
}
?>
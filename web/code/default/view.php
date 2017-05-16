<?php
/**
 * 文章阅读
 * @author      funfly
 * @email       funfly@qq.com
 * @copyright   FunPHP
 * @version     1.0.0
 * @since       2010-03-19
 */
class view {
    public $articleTable = "the_article";   //表名
    public $sortTable = "the_sort";         //分类表名
    public $results;
    public $sid;
    public $ssid = 0;
    public $aid;
    public $msg;
    
    function __construct() {
        global $db;
        
        $sid = (int)$_REQUEST['sid'];
        $ssid = (int)$_REQUEST['ssid'];
        $aid = $_REQUEST['aid'];
        $msg = trim($_REQUEST['msg']);
        
        $articleTable = $this->articleTable;
        
        if($ssid < 1){
            $ssid = $sid;
        }
        
        $this->sid = $sid;
        $this->ssid = $ssid;
        $this->aid = $aid;
        $this->msg = $msg;
        
        $this->getArticle($aid);
    }
    
    /**
     *参数：$aid: 文章ID号
     *示例：$results = $obj->getArticle($aid);
     *返回：获取文章内容
    */
    function getArticle($aid){
        global $db;
        
        $articleTable = $this->articleTable;
        $aid = $this->aid;
        
        $results = $db->get($articleTable,$aid);
        if(count($results) > 0){
            if($results[0]['check'] && $results[0]['flag'] ){
                $hits = $results[0]['hits']+1;
                $dataArray = array(
                    $aid,
                    array('hits'=>$hits)
                );
                $db->update($articleTable,$dataArray);
            }else{
                $results = array();
            }
        }
        $this->results = $results;
        
    }
    
    
}

$obj = new view;
$results = $obj->results;
$sid = $obj->sid;
$ssid = $obj->ssid;
$aid = $obj->aid;
$msg = $obj->msg;

$sorts = new sorts;
$sortsTree = $sorts->getSortsTree($sid,$ssid);
$bigSortname = $sorts->bigSortname;
$bigSid = $sorts->bigSid;
$curSortname = $sorts->curSortname;
$curSid = $sorts->curSid;


include './templates/'.$tempDir.'/'.$pagedo.'.htm';
?>
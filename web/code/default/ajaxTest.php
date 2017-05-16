<?php
/**
 * AJAX测试
 * @author      funfly
 * @email       funfly@qq.com
 * @copyright   FunPHP
 * @version     1.0.0
 * @since       2010-03-19
 */
class ajaxTest {
    
    public $articleTable = "the_article";   //表名
    public $aid;
    public $results = array();
    
    function __construct() {
        
        $aid = (int)$_REQUEST['aid'];
        $this->aid = $aid;
        
        if($aid > 0){
            $this->getArticle();
        }
    }
    
    /**
     *示例：$obj->getArticle();
     *返回：文章内容
    */
    function getArticle(){
        global $db;
        
        $aid = $this->aid;
        $articleTable = $this->articleTable;
        $results = $this->results;
        
        $results['aid'] = $aid;
        
        $data = $db->get($articleTable,$aid);
        if(count($data) > 0){
            $results['code'] = 1;
            $results['msg'] = "成功读取文章";
            $results['data'] = $data;
        }else{
            $results['code'] = 0;
            $results['msg'] = "文章不存在";
            $results['data'] = $data;
        }
        
        $this->results = $results;
    }
    
}

$obj = new ajaxTest;
$aid = $obj->aid;
$results = $obj->results;

include './templates/'.$tempDir.'/'.$pagedo.'.htm';
?>
<?php 
set_time_limit(0);                      //超时设置：采集大量数据时用到
//ini_set('date.timezone','Asia/Shanghai');
ini_set("track_errors" ,true);			       //开启错误
date_default_timezone_set('PRC');       //设置时区
header('Content-type: application/json; charset=utf-8');
include '../config/config.php';
include '../libraries/utils.php';
include '../libraries/mysqli.php';
include '../libraries/image.php';

set_error_handler("errorHandler");
register_shutdown_function("fatalHandler");

$host = $_SERVER['HTTP_HOST'];

$_REQUEST = $_GET + $_POST;
$_REQUEST = myAddslashes($_REQUEST);   //简单的防注入

function response($ret){
    if( ! is_array($ret)){
        if(empty($ret)){
            $ret = array();
        }else{
            $ret = array('message' => htmlspecialchars($ret));
        }
    }
    if( ! isset($ret['code'])){     //补齐code
        if( ! count($ret)){
            $ret['code'] = 404;
        }else{
            $ret['code'] = 200;
        }
    }
    $ret = myjson_encode($ret);   
    $ret = str_replace(chr(10),'',$ret); 
    $ret = str_replace(chr(13),'',$ret);
    $ret = str_replace("\'",chr(39),$ret); 
    echo $ret;
    exit;
}

//note 禁止对全局变量注入
if (isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) {
    response('请返回重试！code: 1001');
}

$db = new mysql;
$db->connect($dbConfig[0]['dbhost'], $dbConfig[0]['dbuser'], $dbConfig[0]['dbpw'], $dbConfig[0]['dbname'], $dbConfig[0]['pconnect'], $dbConfig[0]['dbcharset']);

function make_thumb($path){
    if(!$path || $path == 'NULL' ){
        return '';    
    }
    if(count(explode('_original',$path)) > 1){
        return $path;
    }
    if(!file_exists('../../'.$path)){
        return $path;
    }
    
    $arr = explode('.', $path);
    
    $originalPath = '../../'.$arr[0].'_original.'.$arr[1];
    $image = new image('../../'.$path);
    $image->resizeImage(640,640);
    $image->save(1,$originalPath);
    
    $thumbPath = '../../'.$arr[0].'_thumb.'.$arr[1];
    $image = new image('../../'.$path);
    $image->resizeImage(240,240);
    $image->save(1,$thumbPath);
    
    unlink('../../'.$path);
    
    $newPath = $arr[0].'_original.'.$arr[1];
    return $newPath;
    
    /*
    $arr = explode('.', $path);
    $newPath = $arr[0].'_original.'.$arr[1];
    rename('../../'.$path, '../../'.$newPath);
    
    $thumbPath = '../../'.$arr[0].'_thumb.'.$arr[1];
    
    $image = new image('../../'.$newPath);
    $image->resizeImage(240,240);
    $image->save(1,$thumbPath);
    return $newPath;
    */
}

$sql = "SELECT `goodsid`,`image`, `image1`, `image2`, `image3` FROM `the_goods` ORDER BY `goodsid` ";
$res = $db->getX($sql);
foreach($res as $goods){
    $data = array(
        'image' => make_thumb($goods['image']),
        'image1' => make_thumb($goods['image1']),
        'image2' => make_thumb($goods['image2']),
        'image3' => make_thumb($goods['image3'])
    );
    $db->update('the_goods', array($goods['goodsid'],$data));

}
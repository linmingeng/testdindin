<?php
/**
 * 上传文件
 * @author      funfly
 * @email       funfly@qq.com
 * @copyright   FunPHP
 * @version     1.0.0
 * @since       2010-03-19
 */
$obj = new upload;
$obj->minattachsize = 20480;     //最小上传大小
$obj->maxattachsize = 204800;    //最大上传大小
$obj->upext = 'jpg,jpeg,gif';    //上传扩展名:txt,rar,zip,jpg,jpeg,gif,png,swf,wmv,avi,wma,mp3,mid
$results = $obj->uploadNow();

$results = json_encode($results);
echo $results;
?>
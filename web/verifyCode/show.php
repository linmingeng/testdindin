<?php
/**
 * 生成图片验证码
 * @author      funfly
 * @email       funfly@qq.com
 * @copyright   FunPHP
 * @version     1.0.0
 * @since       2010-03-19
 */
 
include 'verifyCode.php';

$sn = $_GET['sn'];
$sn = htmlspecialchars($sn);
$obj = new verifyCode;
$obj->show($sn);
?>
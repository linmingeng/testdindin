<?php
error_reporting(E_ERROR);
require_once 'phpqrcode.php';
$data = urldecode($_GET["data"]);
QRcode::png($data);  //生成二维码
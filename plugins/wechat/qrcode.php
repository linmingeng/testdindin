<?php
error_reporting(E_ERROR);
require_once '../qrcode/phpqrcode.php';
$url = urldecode($_GET["data"]);
QRcode::png($url);

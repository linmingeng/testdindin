<?php
require_once __DIR__ . '/Common.php';

use OSS\OssClient;
use OSS\Core\OssException;

$bucket = Common::getBucketName();
$ossClient = Common::getOssClient();
if (is_null($ossClient)) exit(1);
echo '<pre>';
    
$object = "storage/302/qrcode_for_gh_71d685c0992e_344.jpg";
$acl = "public-read";   // ['default', 'private', 'public-read', 'public-read-write']
try {
    //$res = $ossClient->putObjectAcl($bucket, $object, $acl);
    //$res = $ossClient->getObjectAcl($bucket, $object);
    //$res = $ossClient->listObjects($bucket);
    $res = $ossClient->listObjects($bucket, array('prefix' => 'storage/'));
    print_r($res);
} catch (OssException $e) {
    printf($e->getMessage() . "\n");
    return;
}

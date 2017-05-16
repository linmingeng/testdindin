<?php
require_once BASE_PATH.'/modules/versions_module.php';
/**
 * 版本相关控制层
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2013-10-29
 */
class versions {
    //调用网址：[GET] http://localhost/api/index.php/versions/android
    function android_get(){
        $versions_module = new versions_module();
        return $versions_module->getNewVersion(0);
    }
    //调用网址：[GET] http://localhost/api/index.php/versions/ios
    function ios_get(){
        $versions_module = new versions_module();
        return $versions_module->getNewVersion(1);
    }
}
<?php
if(!isset($_SESSION)){
    session_start();
}

//note 定义funREST框架基本常量
define('IN_FUNREST', TRUE);
//note 正在被访问的文件路径，例如：D:\web\funREST
define('BASE_PATH', substr(__FILE__, 0, -18));
//note 设置数据加密密钥
define('ENCODE_SECRET', 'funREST');
//note 设置部署网址
define('BASE_URL', 'http://www.dindin.com/plugins');

define('SITE_DOMAIN', 'dindin.com');

//通过域名获取appid
function getAppid($domain){
    preg_match_all ("|(\d*)\.dindin\.com|U", $domain, $match);
    if(isset($match[1]) && isset($match[1][0]) ){
        $appid = intval($match[1][0]);
    }else{
        $appid = 0;
    }
    return $appid;
}

$debug = "on";  //开启调试模式: on=>开启,off=>关闭
$logDir = BASE_PATH."/logs/";   //定义log目录

//当前应用程序数据库连接参数
$dbConfig[0] = array(
    'dbhost' => 'dd.mysql.rds.aliyuncs.com:3306',		// 数据库服务器
    'dbuser' => 'dd',			// 数据库用户名
    'dbpw' => 'dd520888!@#$',				// 数据库密码
    'dbname' => 'the_dd',			// 数据库名
    'pconnect' => 0,				// 数据库持久连接 0=关闭, 1=打开
    'dbcharset' => 'utf8',			// MySQL 字符集, 可选 'gbk', 'big5', 'utf8', 'latin1', 留空为按照论坛字符集设定
);

$secret_key = 'YSDID786sAPPfwhcom20160311';        //内部数据完整性验证密钥

//同步登录 Cookie 设置
$cookiepre = 'dd_';     //cookie 前缀
$cookiedomain = '';     //cookie 作用域
$cookiepath = '/';      //cookie 作用路径
$cookiettl = 86400;  //cookie 过期时间

//缓存时间(s)
$cacheTime = 0;         //24*3600;

//是否伪静态化
$static = 0;

//====================siteConfig====================
$siteConfig = array(
    'siteName' => '叮叮网',
    'siteUrl' => 'www.dindin.com',
    'keywords' => '',
    'describe' => '',
    'bbsUrl' => 'localhost',
    'uchomeUrl' => 'localhost',
    'ucenterUrl' => 'localhost',
    'icp' => '闽ICP备17003323号-1',
    'copyright' => 'Copyright (c) 2012 <a href=\'http://www.dindin.com\'>叮叮网</a> All Rights<br>闽ICP备17003323号-1',
    'friendLinks' => '',
    'man' => '吴先生',
    'address' => '',
    'zip' => '',
    'phone' => '',
    'mobile' => '',
    'fax' => '',
    'qq' => '',
    'msn' => '',
);

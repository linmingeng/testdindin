<?php
/**
 * 函数库
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2013-10-29
 */
!defined('IN_FUNREST') && exit('Access Denied');

//获取参数
function makeUrl($url){
    if( ! $url){
        return ;    
    }
    if(strstr($url,'http://') === false){
        return BASE_URL.$url;
    }
    return $url;
}

//获取参数
function request($key){
    if( ! $key){
        return ;    
    }
    if(isset($_REQUEST[$key])){
        return $_REQUEST[$key];
    }
}

//数组过滤
function raw_array($data){
    if(is_array($data)){
        foreach($data as $key => $val){
            $data[$key] = raw_array($val);
        }
        return $data;
    }else{
        return raw_str($data);
    }
}

//字符串过滤
function raw_str($str){
    return trim(htmlspecialchars($str));
}

/*
函数名：cutstr
功  能：截断字符串。
参  数：$str——普通字符串 $len——要截断的字数
返  回：返回已经截断的字符串
*/
function cut_str($str,$len){
    for($i=0;$i<$len;$i++){
        $temp_str=substr($str,0,1);
        if(ord($temp_str) > 127){
            $i++;
            if($i<$len){
                $new_str[]=substr($str,0,3);
                $str=substr($str,3);
            }
        }
        else{
            $new_str[]=substr($str,0,1);
            $str=substr($str,1);
        }
    }
    return join($new_str);
}

//处理json_encode时中文变成UTF8编码的问题
function myjson_encode($code,$raw = 1){
    $code = json_encode(urlencodeArray($code,$raw));
    return urldecode($code);
}
function myjson_decode($code){
    if(!$code){
        return array();
    }
    $code = str_replace('\"','"',$code);
    if( gettype(json_decode($code,1)) != 'array'){
        return array();
    }else{
        return json_decode($code,1);
    }
}
function urlencodeArray($data,$raw = 1){
    if(is_array($data)){
        foreach($data as $key => $val){
            $data[$key] = urlencodeArray($val,$raw);
        }
        return $data;
    }else{
        if($raw){
            return urlencode(addslashes(stripslashes($data)));
        }
        return urlencode($data);
    }
}

/**
 * 根据key及系统所使用的语言，返回信息
 * @param string     $key    文字对应的key
 * @param array     $para    输入参数
 * @return string    返回结果
 */
function lang($key,$para=array()){
    $lang = & $GLOBALS['lang'];
    if(key_exists($key,$lang)){
        return vsprintf($lang[$key],$para);
    } else {
        return '';
    }
}

//获取访问者IP
function getIp(){
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) return $_SERVER['HTTP_X_FORWARDED_FOR'];
    if (isset($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
    if (isset($_SERVER['REMOTE_ADDR'])) return $_SERVER['REMOTE_ADDR'];
    return '';
}

/**
 * 从数组中剔除某些KEY的值
 */
function removeFromArray(& $targetArray, $keyList){
    if(!is_array($targetArray)) return;
    if (count($targetArray) != count($targetArray,COUNT_RECURSIVE)){
        foreach($targetArray as & $arr){
            removeFromArray($arr,$keyList);
        }
    } else {
        foreach($keyList as $key){
            unset($targetArray[$key]);
        }
    }
}

/**
 * 从数组中提取某些KEY的值
 */
function fetchFromArray(& $targetArray, $keyList){
    if(!is_array($targetArray)) return;
    if (count($targetArray) != count($targetArray,COUNT_RECURSIVE)){
        foreach($targetArray as & $arr){
            fetchFromArray($arr,$keyList);
        }
    } else {
        foreach($targetArray as $k=>$v){
            if(!in_array($k,$keyList)){
                unset($targetArray[$k]);
            }
        }
    }
}
//移除非法字符 ~!@#$%^&*()_+{}:|<>?,./;'[]\-=`
function removeSpecialChar($str){
    $regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
    return preg_replace($regex,"",$str);
}
/**
 * 在保存html的时候，清理一些可能的隐患
 * 这里的清除仍然还是不够全，有时间谁可以看一看http://ha.ckers.org/xss.html里面对xss攻击的所有可能脚本
 * 所以一般还是不要保存html了。还是用bbcode吧。
 * @param string $str
 * @return string
 */
function removeScript($str){
    $f = array(
    "/<(?:link|script|frame|iframe|frameset)[^>]*>.*?<\/(?:link|script|frame|iframe|frameset)\s*>/is",
    "/<(?:frameset|script|iframe|frame|link)[^>]*>/is",
    "/<a\s*href=\s*['|\"|j|v].*?script:[^>]*>.*?<\/a>/is",
    "/<[a-z]+\s*(?:onerror|onload|onunload|onresize|onblur|onchange|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup|onmousemove|onmousedown|onmouseout|onmouseover|onmouseup|onselect)[^>]*>/is"
    );
    return preg_replace($f,'',$str);
}

//格式化时间
function formateTime($time){

    $diff = floor((time() - $time));
    $dayDiff = floor($diff / 86400);
    $str = '';
    if($diff < 60){
        $str = '刚刚';
    }else if($diff < 120){
        $str = '1分钟前';
    }else if($diff < 3600){
        $str = floor($diff/60).'分钟前';
    }else if($diff < 7200){
        $str = '1小时前';
    }else if($diff < 86400){
        $str = floor($diff/3600).'小时前';
    }else if($dayDiff == 1){
        $str = '昨天';
    }else if($dayDiff == 2){
        $str = '前天';
    }else if($dayDiff < 7){
        $str = $dayDiff.'天前';
    }else if($dayDiff < 31){
        $str = ceil($dayDiff/7).'周前';
    }else{
        $str = date('Y-m-d',$time);
    }

    return $str;
}
function myAddslashes(&$data){
    if(!get_magic_quotes_gpc()){
        if(is_array($data)){
            return array_map('myAddslashes',$data);
        }else{
            $data = addslashes($data);
            $data = nl2br($data); 
            //$data = htmlspecialchars($data); 
            return $data;
        }
        //return is_array($data)?array_map('myAddslashes',$data):addslashes($data);
    }else{
        return $data;
    }
}

function error($code = 404, $msg = '', $key = 'msg'){
    if( $code == 404 ){
        header ( "HTTP/1.0 404 Not Found");
        exit;
    }else if( $code == 500 ){
        header ( "HTTP/1.0 500 Internal Server Error");
        exit;
    } 
    $ret = array("code" => $code);
    if($msg){
        $ret[$key] = $msg;
    }
    echo json_encode($ret);
    exit;
}

function location($url){
    header('Location: '.$url);
    exit;
}

function mySetcookie($var, $value, $life = 0, $prefix = 1) {
    global $cookiepre, $cookiedomain, $cookiepath, $_SERVER;
    $timestamp = time();
    setcookie(($prefix ? $cookiepre : '').$var, $value,
        $life ? $timestamp + $life : 0, $cookiepath,
        $cookiedomain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
}

function myEncode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

    $ckey_length = 4;

    $key = md5($key ? $key : ENCODE_SECRET);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if($operation == 'DECODE') {
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc.str_replace('=', '', base64_encode($result));
    }
}

//捕捉致命错误
function fatalHandler() {
    $error = error_get_last();
    if($error) {
        $errno   = $error["type"];
        $errfile = $error["file"];
        $errline = $error["line"];
        $errstr  = $error["message"];
        errorHandler( $errno, $errstr, $errfile, $errline);
    }
}
//捕捉异常
function errorHandler( $errno, $errstr, $errfile, $errline) {
    global $debug,$logDir;
    if($debug != 'on'){
        return ;    
    }
    if($errno == 2 || $errno == 8){ //2	E_WARNING 8	E_NOTICE
        return ;    
    }
    //$trace = debug_backtrace( false );
    //$trace = print_r( $trace, true );
/*
2	E_WARNING	非致命的 run-time 错误。不暂停脚本执行。
8	E_NOTICE	
    Run-time 通知。
    脚本发现可能有错误发生，但也可能在脚本正常运行时发生。
256	E_USER_ERROR	致命的用户生成的错误。这类似于程序员使用 PHP 函数 trigger_error() 设置的 E_ERROR。
512	E_USER_WARNING	非致命的用户生成的警告。这类似于程序员使用 PHP 函数 trigger_error() 设置的 E_WARNING。
1024	E_USER_NOTICE	用户生成的通知。这类似于程序员使用 PHP 函数 trigger_error() 设置的 E_NOTICE。
4096	E_RECOVERABLE_ERROR	可捕获的致命错误。类似 E_ERROR，但可被用户定义的处理程序捕获。(参见 set_error_handler())
8191	E_ALL	
    所有错误和警告，除级别 E_STRICT 以外。
    （在 PHP 6.0，E_STRICT 是 E_ALL 的一部分）
*/
    $errormsg = "
<table>
<tbody>
    <tr>
        <th>Time</th>
        <td>".date('y-m-d H:i:s')."</td>
        <th>Error</th>
        <td><pre>$errstr</pre></td>
        <th>Errno</th>
        <td><pre>$errno</pre></td>
        <th>File</th>
        <td>$errfile</td>
        <th>Line</th>
        <td>$errline</td>
    </tr>
</tbody>
</table>";
    
    $fileName = $logDir.str_replace(".","",$_SERVER['REMOTE_ADDR']).".html";
    $openType = "a+";
    $fp = fopen($fileName,$openType);
    fwrite($fp,$errormsg);
    fclose($fp);
    error(500);
}

function mylog($msg, $filename){
    if(!$filename){
        $filename = 'log';
    }
    $file = './logs/'.$filename.'_'.date('Y-m-d').'.txt';
    $handle = fopen($file,'a');
    fwrite($handle, $msg, 4096);
    fclose($handle);
}

?>
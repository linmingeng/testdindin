<?php
/**
 * OSS上传策略
 * @author      funfly
 * @email       echo@funfly.cn
 * @copyright   funREST
 * @version     1.0.0
 * @since       2016-08-26
 */
require_once __DIR__.'/../Config.php';

class policy {
    
    public $userid = 0;
    public $rootDir = 'storage/';
    public $id = Config::OSS_ACCESS_ID;
    public $key = Config::OSS_ACCESS_KEY;
    public $endpoint = Config::OSS_ENDPOINT;
    public $bucket = Config::OSS_BUCKET;
    public $callbackUrl = 'http://www.dindin.com/plugins/oss/uploadNotify.php';
    public $callbackHost = 'www.dindin.com';
    public $minSize = 0;
    public $maxSize = 1073741824;   //1G
    
    function __construct() {

    }
    
    function gmt_iso8601($time) {
        $dtStr = date("c", $time);
        $mydatetime = new DateTime($dtStr);
        $expiration = $mydatetime->format(DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration."Z";
    }
    
    public function get() {
        
        $userid = $this->userid;
        $rootDir = $this->rootDir;
        $dir = $rootDir.$userid.'/';
        $id = $this->id;
        $key = $this->key;
        $host = 'http://'.$this->bucket.'.'.$this->endpoint;
        $callbackUrl = $this->callbackUrl;
        $callbackHost = $this->callbackHost;
        $minSize = $this->minSize;
        $maxSize = $this->maxSize;
        
        $callback_body = '{"callbackUrl":"'.$callbackUrl.'","callbackHost":"'.$callbackHost.'","callbackBody":"bucket=${bucket}&object=${object}&etag=${etag}&size=${size}&mime=${mimeType}&height=${imageInfo.height}&width=${imageInfo.width}&format=${imageInfo.format}&userid='.$userid.'&dir='.$dir.'","callbackBodyType":"application/x-www-form-urlencoded"}';
        $base64_callback_body = base64_encode($callback_body);
        $now = time();
        $expire = 30; //设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问
        $end = $now + $expire;
        $expiration = $this->gmt_iso8601($end);
    
        //最大文件大小.用户可以自己设置
        $condition = array(0=>'content-length-range', 1=>$minSize, 2=>$maxSize);
        $conditions[] = $condition; 
    
        //表示用户上传的数据,必须是以$dir开始, 不然上传会失败,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
        $start = array(0=>'starts-with', 1=>'$key', 2=>$dir);
        $conditions[] = $start; 
    
        $arr = array('expiration'=>$expiration,'conditions'=>$conditions);
        //echo json_encode($arr);
        //return;
        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $string_to_sign = $base64_policy;
        $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $key, true));
    
        $response = array();
        $response['accessid'] = $id;
        $response['host'] = $host;
        $response['policy'] = $base64_policy;
        $response['signature'] = $signature;
        $response['expire'] = $end;
        $response['callback'] = $base64_callback_body;
        //这个参数是设置用户上传指定的前缀
        $response['dir'] = $dir;
        
        return $response;
    }

}
?>
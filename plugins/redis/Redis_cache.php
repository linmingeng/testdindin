<?php
/**
 * Redis的cache操作类
 *
 * @author funfly
 *
 */
class Redis_cache extends Redis_operator {

    function set($key, $ret, $seconds = 30)
    {
        if( ! $key){
            return ;    
        }
        
        if( ! is_array($ret)){
            if(empty($ret)){
                $ret = array();
            }else{
                $ret = array('msg' => htmlspecialchars($ret));
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
        $val = $ret;
        
        $time = gmdate('D, d M Y H:i:s') . ' GMT';
        $data = array(
            'data' => $val,
            'time' => $time
        );
        $ret = $this->redis_db->hmset($key, $data);
        if($seconds){
            $this->redis_db->expire($key, $seconds);
        }
        //header('Content-type: text/plain; charset=utf-8');
        header("Expires: ".gmdate('D, d M Y H:i:s', time() + $seconds) . ' GMT');
        header('Last-Modified:'.$time);
        header('Cache-Control:max-age='.$seconds);
        header('ETag:'.md5($key.$time));
        echo $val;
        exit();
    }

    function get($key)
    {
        global $controller,$method;
        $time = $this->redis_db->hget($key,'time');
        if(empty($time) ){
            return ;    
        }
        $seconds = 30;         //5分钟后过期
        if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])){
            $modifiedTime = $_SERVER['HTTP_IF_MODIFIED_SINCE'];
            if($time == $modifiedTime){
                //header('Content-type: text/plain; charset=utf-8');
                header("Expires: ".gmdate('D, d M Y H:i:s', time() + $seconds) . ' GMT');
                header('Last-Modified:'.$time);
                header('Cache-Control:max-age='.$seconds);
                header('ETag:'.md5($key.$time));
                header('HTTP/1.0 304 Not Modified');
                exit();
            }
        }
        $data = $this->redis_db->hget($key,'data');
        if(empty($data)){
            return ;    
        }
        //header('Content-type: text/plain; charset=utf-8');
        header("Expires: ".gmdate('D, d M Y H:i:s', time() + $seconds) . ' GMT');
        header('Last-Modified:'.$time);
        header('Cache-Control:max-age='.$seconds);
        header('ETag:'.md5($key.$time));
        echo $data;
        exit();
    }

    function hgetall($key)
    {
        return $this->redis_db->hgetall($key);
    }

    function del($key)
    {
        return $this->redis_db->del($key);
    }
}

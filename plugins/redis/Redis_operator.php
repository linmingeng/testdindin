<?php

/**
 * Redis操作基类
 *
 * @author JiangXh
 * @package Redis
 */
class Redis_operator {

    protected $redis_db = null;
    private $now_config = 'a';

    function __construct($redis_name = 'default')
    {
        global $redis_config;
        global $default_redis_adapter;

        $config           = isset($redis_config[$redis_name]) ? $redis_config[$redis_name] : $redis_config['default'];
        $this->now_config = $redis_config;

        if ($redis_name == 'default' && $default_redis_adapter)
        {
            $this->redis_db = $default_redis_adapter;
        }
        else
        {
            $this->redis_db = new Redis_adapter($config['master']);
        }
    }

}

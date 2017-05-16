<?php
define('REDIS_PATH', dirname(__FILE__) . '/');

require_once REDIS_PATH . '/lib/Predis/Autoloader.php';
Predis\Autoloader::register();

require_once REDIS_PATH . 'Redis_adapter.php';
require_once REDIS_PATH . 'Redis_operator.php';
require_once REDIS_PATH . 'Redis_cache.php';



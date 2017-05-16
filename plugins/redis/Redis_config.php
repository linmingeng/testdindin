<?php
$redis_config = array(
    'default' =>
    array(
        'master' => array(
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'database' => ''
        ),
        'slave'  => array(
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'database' => ''
        )
    ),
    'mq'      =>
    array(
        'master' => array(
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'database' => ''
        )
    )
);
?>
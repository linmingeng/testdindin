#!/bin/bash

process=`ps aux | grep 'auto_deal_orders.php' | grep -v grep`;

if [ "$process" == "" ]; then
    nohup /usr/local/php/bin/php /data/www/api/auto_deal_orders.php > /data/www/api/logs/auto_deal_orders.txt &
    echo 'auto_deal_orders.php start... ';
else
    echo 'auto_deal_orders.php is runing ';
fi

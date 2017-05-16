#!/bin/bash

process=`ps aux | grep 'trader_report_daily.php' | grep -v grep`;

if [ "$process" == "" ]; then
    nohup /usr/local/php/bin/php /data/www/api/trader_report_daily.php > /data/www/api/logs/trader_report_daily.txt &
    echo 'trader_report_daily.php start... ';
else
    echo 'trader_report_daily.php is runing ';
fi

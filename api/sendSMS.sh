#!/bin/bash

process=`ps aux | grep 'send_sms.php' | grep -v grep`;

if [ "$process" == "" ]; then
    nohup /usr/local/php/bin/php /data/www/api/send_sms.php > /data/www/api/logs/send_sms.txt &
    echo 'send_sms.php start... ';
else
    echo 'send_sms.php is runing ';
fi

#/usr/bin/bash

# make cron directory
mkdir /home/mo-bot
mkdir /home/mo-bot/src
mkdir /home/mo-bot/conf
mkdir /home/mo-bot/logs
mkdir /home/mo-bot/work

# cp cron source
cp ../src/* /home/mo-bot/src/
cp ../conf/cron.conf /home/mo-bot/conf/

# cron setting
crontab /home/mo-bot/conf/cron.conf

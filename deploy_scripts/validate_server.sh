#!/bin/bash
status_code=$(curl --silent --head 0.0.0.0:5000/ | grep HTTP/ | awk -F ' ' '{ print $2 }')
echo $status_code
echo $(ps aux | grep /usr/local/bin/flask | grep -v grep | awk '{print $2}')
if [[ $status_code == "200" ]]; then
    exit 0
else
    exit 1
fi

#!/bin/bash
status_code=$(curl --silent --head 0.0.0.0:8080/ | grep HTTP/ | awk -F ' ' '{ print $2 }')
echo $status_code
ps aux | grep docker-compose | grep -v grep 
if [[ $status_code == "200" ]]; then
    exit 0
else
    exit 1
fi

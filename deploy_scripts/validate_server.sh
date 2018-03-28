#!/bin/bash
n=0
until [ $n -ge 5 ]
do
  (curl -k --silent --head https://0.0.0.0/ && break) || true
  n=$[$n+1]
  sleep 5
done
status_code=$(curl -k --silent --head https://0.0.0.0/ | grep HTTP/ | awk -F ' ' '{ print $2 }')
if [[ $status_code == "200" ]]; then
    exit 0
else
    exit 1
fi

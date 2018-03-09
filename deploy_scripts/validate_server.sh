#!/bin/bash
status_code=$(curl -k --silent --head https://0.0.0.0/ | grep HTTP/ | awk -F ' ' '{ print $2 }')
if [[ $status_code == "200" ]]; then
    exit 0
else
    exit 1
fi

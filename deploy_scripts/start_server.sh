#!/bin/bash
echo "Starting Flask on EC2 Instance..."
cd /home/ubuntu/skitter/
/usr/local/bin/docker-compose up >/dev/null 2>&1 &

sleep 10

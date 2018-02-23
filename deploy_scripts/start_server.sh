#!/bin/bash
echo "Starting Flask on EC2 Instance..."
cd /opt/skitter/
sudo /usr/local/bin/docker-compose build
sudo mkdir -p /var/log/skitter/docker
sudo /usr/local/bin/docker-compose up >/var/log/skitter/docker/stdout 2>/var/log/skitter/docker/stderr &

sleep 10

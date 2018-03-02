#!/bin/bash
echo "Starting Flask on EC2 Instance..."
cd /opt/skitter/
sudo mkdir -p /var/log/skitter/docker
sudo touch /var/log/skitter/docker/stdout
sudo touch /var/log/skitter/docker/stderr
sudo chown -R ec2-user:ec2-user /var/log/skitter/
sudo /usr/local/bin/docker-compose up --build >> /var/log/skitter/docker/stdout 2>> /var/log/skitter/docker/stderr &

sleep 10

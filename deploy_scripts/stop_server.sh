#!/bin/bash
echo "Stopping Docker on EC2 Instance..."
cd /home/ubuntu/skitter/
/usr/local/bin/docker-compose kill

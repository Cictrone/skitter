#!/bin/bash
echo "Stopping Docker on EC2 Instance..."
cd /opt/skitter/
sudo /usr/local/bin/docker-compose kill

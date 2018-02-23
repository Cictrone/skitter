#!/bin/bash
echo "Stopping Docker on EC2 Instance..."
cd /home/ubuntu/skitter/
docker-compose kill

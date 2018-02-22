#!/bin/bash
echo "Starting Flask on EC2 Instance..."
python3 /home/ubuntu/skitter/app.py > /dev/null 2> /dev/null < /dev/null &

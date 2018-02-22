#!/bin/bash
echo "Stopping Flask on EC2 Instance..."
ps -ef | grep app.py | grep -v grep | awk '{print $2}' | xargs kill -9

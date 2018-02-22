#!/bin/bash
echo "Starting Flask on EC2 Instance..."
export FLASK_APP=/home/ubuntu/skitter/app.py
/usr/local/bin/flask run --host=0.0.0.0 >/dev/null 2>&1 &

sleep 5

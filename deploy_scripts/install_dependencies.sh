#!/bin/bash
echo "Installing Python/Pip Dependencies on EC2 Instance..."
apt-get install python3-pip
apt-get install python3
pip3 install -r /home/ubuntu/skitter/requirements.txt

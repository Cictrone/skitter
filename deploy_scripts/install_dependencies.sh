#!/bin/bash
echo "Installing Pip Dependencies on EC2 Instance..."
ls -la home/ubuntu/skitter/
pip3 install -r home/ubuntu/skitter/requirements.txt

#!/bin/bash
echo "Installing Pip Dependencies on EC2 Instance..."
ls -la
pwd
ls -la ../
pip3 install -r ../requirements.txt

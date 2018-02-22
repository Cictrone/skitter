#!/bin/bash
echo "Installing Pip Dependencies on EC2 Instance..."
pwd
ls -la deployment-root
pip3 install -r ../requirements.txt

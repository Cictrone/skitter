#!/bin/bash
echo "Installing Python/Pip Dependencies on EC2 Instance..."
apt-get -y install python3-pip
apt-get -y install python3
pip3 install -r /home/ubuntu/skitter/requirements.txt
iptables -A INPUT -p tcp -m multiport --dports 80,443,5000 -m conntrack --ctstate NEW,ESTABLISHED -j ACCEPT
iptables -A OUTPUT -p tcp -m multiport --dports 80,443,5000 -m conntrack --ctstate ESTABLISHED -j ACCEPT

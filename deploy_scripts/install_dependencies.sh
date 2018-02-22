#!/bin/bash
echo "Installing Python/Pip Dependencies on EC2 Instance..."
yum -y update
yum install epel-release -y
yum install ruby -y
yum install python35 -y
yum install python35-pip -y
pip-3.5 install -r /home/ubuntu/skitter/requirements.txt
iptables -A INPUT -p tcp -m multiport --dports 80,443,5000 -m conntrack --ctstate NEW,ESTABLISHED -j ACCEPT
iptables -A OUTPUT -p tcp -m multiport --dports 80,443,5000 -m conntrack --ctstate ESTABLISHED -j ACCEPT

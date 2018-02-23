#!/bin/bash
echo "Installing Python/Pip Dependencies on EC2 Instance..."
cd /home/ubuntu/skitter/
yum -y update
yum install epel-release -y
yum install ruby -y
yum install python35 -y
yum install python35-pip -y
sudo yum install -y docker
sudo usermod -a -G docker ec2-user
sudo curl -L https://github.com/docker/compose/releases/download/1.9.0/docker-compose-`uname -s`-`uname -m` | sudo tee /usr/local/bin/docker-compose > /dev/null
sudo chmod +x /usr/local/bin/docker-compose
sudo service docker start
sudo chkconfig docker on
pip-3.5 install -r requirements.txt
iptables -A INPUT -p tcp -m multiport --dports 80,443,8080 -m conntrack --ctstate NEW,ESTABLISHED -j ACCEPT
iptables -A OUTPUT -p tcp -m multiport --dports 80,443,8080 -m conntrack --ctstate ESTABLISHED -j ACCEPT

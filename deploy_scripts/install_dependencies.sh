#!/bin/bash
echo "Installing Python/Pip Dependencies on EC2 Instance..."
cd /opt/skitter/
sudo yum -y update
sudo yum install epel-release -y
sudo yum install ruby -y
sudo yum install python35 -y
sudo yum install python35-pip -y
sudo yum install -y docker
sudo usermod -a -G docker ec2-user
sudo curl -L https://github.com/docker/compose/releases/download/1.17.0/docker-compose-`uname -s`-`uname -m` | sudo tee /usr/local/bin/docker-compose > /dev/null
sudo chmod +x /usr/local/bin/docker-compose
sudo service docker start
sudo chkconfig docker on
sudo pip-3.5 install -r requirements.txt
sudo iptables -A INPUT -p tcp -m multiport --dports 80,443 -m conntrack --ctstate NEW,ESTABLISHED -j ACCEPT
sudo iptables -A OUTPUT -p tcp -m multiport --dports 80,443 -m conntrack --ctstate ESTABLISHED -j ACCEPT

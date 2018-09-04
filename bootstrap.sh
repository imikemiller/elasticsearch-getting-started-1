#!/usr/bin/env bash

# add java ppa
sudo add-apt-repository ppa:webupd8team/java
sudo apt-get update

# accept the license
echo debconf shared/accepted-oracle-license-v1-1 select true | sudo debconf-set-selections
echo debconf shared/accepted-oracle-license-v1-1 seen true | sudo debconf-set-selections

# install java
sudo apt-get -y install oracle-java8-installer

# set JAVA_HOME
export JAVA_HOME="/usr/lib/jvm/java-8-oracle"
echo "installed Java at: "$JAVA_HOME

# install apt-transport-https
sudo apt-get -y install apt-transport-https
echo "installed apt-transport-https"

# add repo definition
sudo echo "deb https://artifacts.elastic.co/packages/6.x/apt stable main" | sudo tee -a /etc/apt/sources.list.d/elastic-6.x.list

# update apt and install
sudo apt-get update 
sudo apt-get -y --allow-unauthenticated install elasticsearch
echo "installed elasticsearch"
sudo apt-get -y --allow-unauthenticated install kibana
echo "installed kibana"

# configure
chown -R elasticsearch:elasticsearch /var/lib/elasticsearch/

# change host configurations
echo network.host: 0.0.0.0 >> /etc/elasticsearch/elasticsearch.yml
echo server.host: \"0.0.0.0\" >> /etc/kibana/kibana.yml

# start services
sudo service elasticsearch start
sudo service kibana start
echo "services started"

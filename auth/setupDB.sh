#!/bin/bash
apk --update add mariadb mariadb-client
mysql -u root -h skitter-auth-db -pskitter_auth_dbpass -e "create database if not exists Skitter"
mysql -u root -h skitter-auth-db -pskitter_auth_dbpass -e "use Skitter; drop table if exists User; create table User(userId MEDIUMINT NOT NULL AUTO_INCREMENT, displayName varchar(40) NOT NULL, sessionID varchar(33), email varchar(40) NOT NULL, profileImage varchar(40) NOT NULL, UNIQUE(userId,email));"
/usr/bin/java -jar /opt/Auth/target/skitter-auth-0.1.0.jar

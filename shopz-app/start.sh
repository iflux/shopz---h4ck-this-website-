#!/bin/bash

service mysql start

sleep 3

mysql -u root -e "CREATE DATABASE IF NOT EXISTS shopz;"
mysql -u root -e "CREATE USER IF NOT EXISTS 'shopz_user'@'%' IDENTIFIED BY 'shopz_pass123';"
mysql -u root -e "GRANT ALL PRIVILEGES ON shopz.* TO 'shopz_user'@'%';"
mysql -u root -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'root' WITH GRANT OPTION;"
mysql -u root -e "FLUSH PRIVILEGES;"

mysql -u root shopz < /docker-entrypoint-initdb.d/init.sql 2>/dev/null || true

service ssh start
service vsftpd start
service apache2 start

tail -f /var/log/apache2/access.log

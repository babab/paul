#!/usr/bin/env bash

apt-get update
DEBIAN_FRONTEND=noninteractive apt-get install -y apache2 php5 php5-mysqlnd libapache2-mod-php5 mysql-server
rm -rf /var/www
ln -fs /vagrant /var/www

service apache2 restart

mysql -u root -e "SHOW DATABASES;" | grep paul
if [ $? -gt 0 ]; then
    echo paul: creating MySQL database
    mysql -u root -e "CREATE DATABASE paul;"
fi

if [ ! -f /vagrant/paul/config.php ]; then
    echo paul: linking configfile
    ln -s /vagrant/paul/config.vagrant.php /vagrant/paul/config.php
fi

php /vagrant/paul/install.php >/dev/null 2>&1

echo
echo
echo All done... Visit http://localhost:8080/example.php

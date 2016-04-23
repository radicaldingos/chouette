#!/bin/bash

DIR=$1

echo " "
echo "********************"
echo "* Install tools... *"
echo "********************"
php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php
php -r "if (hash_file('SHA384', 'composer-setup.php') === '7228c001f88bee97506740ef0888240bd8a760b046ee16db8f4095c0d8d525f2367663f22a46b48d072c816e7fe19959') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
mv composer.phar /usr/local/bin/composer
chmod u+x /usr/local/bin/composer
apt-get -qq install make php5-curl php5-intl php5-memcache php5-xsl php5-sqlite memcached graphviz

# Set MOTD
if [ -f $DIR_PROVISIONING/etc/motd ];
then
    cp $DIR_PROVISIONING/etc/motd /etc/motd
fi

# Copy useful scripts
#if [ -d $DIR_PROVISIONING/home/vagrant ];
#then
#    cp $DIR_PROVISIONING/home/vagrant/* /home/vagrant
#    chown -R vagrant: /home/vagrant/*
#fi
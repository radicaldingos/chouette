#!/bin/bash

DIR=$1

echo " "
echo "**********************"
echo "* Install Apache2... *"
echo "**********************"

# Apache2 install
apt-get -qq install apache2

# Add vagrant user in www-data group
usermod -aG www-data vagrant

# Set permissions
chown root:www-data /var/www
chmod 775 /var/www

# Activate Apache2 modules
a2enmod expires
a2enmod headers
#a2enmod proxy
#a2enmod proxy_http
a2enmod rewrite
a2enmod ssl
#a2enmod userdir

# Restart service
service apache2 restart

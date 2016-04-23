#!/bin/bash

DIR=$1
PATH_PHP_INI=$2
PATH_PHP_INI_CLI=$3

echo " "
echo "******************"
echo "* Install PHP... *"
echo "******************"
apt-get -qq install php5

if [ -f $PATH_PHP_INI ]; then
    sed -i -e 's/^;date.timezone =$/date.timezone = Europe\/Paris/' $PATH_PHP_INI
    sed -i -e 's/^short_open_tag = On$/short_open_tag = Off/' $PATH_PHP_INI
    sed -i -e 's/^;realpath_cache_size = 16k$/realpath_cache_size = 16k/' $PATH_PHP_INI
    sed -i -e 's/^;realpath_cache_ttl = 120$/realpath_cache_ttl = 120/' $PATH_PHP_INI
    sed -i -e 's/^error_reporting = E_ALL & ~E_DEPRECATED$/error_reporting = E_ALL \& E_STRICT/' $PATH_PHP_INI
    sed -i -e 's/^display_errors = Off$/display_errors = On/' $PATH_PHP_INI
    sed -i -e 's/^track_errors = Off$/track_errors = On/' $PATH_PHP_INI
    sed -i -e 's/^html_errors = Off$/html_errors = On/' $PATH_PHP_INI
    sed -i -e 's/^post_max_size = 2M$/post_max_size = 128M/' $PATH_PHP_INI
    sed -i -e 's/^upload_max_filesize = 2M$/upload_max_filesize = 128M/' $PATH_PHP_INI
    sed -i -e 's/^session.gc_maxlifetime = 1440$/session.gc_maxlifetime = 14400/' $PATH_PHP_INI
    sed -i -e 's/^memory_limit = 128M$/memory_limit = 1024M/' $PATH_PHP_INI
    sed -i -e 's/^SMTP = localhost$/SMTP = smtp1.ba279.air.defense.gouv.fr/' $PATH_PHP_INI
fi
if [ -f $PATH_PHP_INI_CLI ]; then
    sed -i -e 's/^;date.timezone =$/date.timezone = Europe\/Paris/' $PATH_PHP_INI_CLI
    sed -i -e 's/^short_open_tag = On$/short_open_tag = Off/' $PATH_PHP_INI_CLI
    sed -i -e 's/^;realpath_cache_size = 16k$/realpath_cache_size = 16k/' $PATH_PHP_INI_CLI
    sed -i -e 's/^;realpath_cache_ttl = 120$/realpath_cache_ttl = 120/' $PATH_PHP_INI_CLI
    sed -i -e 's/^error_reporting = E_ALL & ~E_DEPRECATED$/error_reporting = E_ALL \& E_STRICT/' $PATH_PHP_INI_CLI
    sed -i -e 's/^display_errors = Off$/display_errors = On/' $PATH_PHP_INI_CLI
    sed -i -e 's/^track_errors = Off$/track_errors = On/' $PATH_PHP_INI_CLI
    sed -i -e 's/^html_errors = Off$/html_errors = On/' $PATH_PHP_INI_CLI
    sed -i -e 's/^post_max_size = 2M$/post_max_size = 128M/' $PATH_PHP_INI
    sed -i -e 's/^upload_max_filesize = 2M$/upload_max_filesize = 128M/' $PATH_PHP_INI
    sed -i -e 's/^session.gc_maxlifetime = 1440$/session.gc_maxlifetime = 14400/' $PATH_PHP_INI_CLI
    sed -i -e 's/^memory_limit = 128M$/memory_limit = 1024M/' $PATH_PHP_INI
    sed -i -e 's/^SMTP = localhost$/SMTP = smtp1.ba279.air.defense.gouv.fr/' $PATH_PHP_INI
fi

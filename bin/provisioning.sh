#!/bin/bash

DIR=$1
DB_NAME=$2
DB_USER=$3
DB_TESTS_NAME=$4
PATH_PHP_INI=/etc/php5/apache2/php.ini
PATH_PHP_INI_CLI=/etc/php5/cli/php.ini
DIR_PROVISIONING=$DIR/bin/provisioning

$DIR_PROVISIONING/start.sh

$DIR_PROVISIONING/install_apache.sh "$DIR"

$DIR_PROVISIONING/install_php.sh "$DIR" "$PATH_PHP_INI" "$PATH_PHP_INI_CLI"

$DIR_PROVISIONING/install_pgsql.sh "$DIR_PROVISIONING" "$DB_NAME" "$DB_USER" "$DB_TESTS_NAME"

#$DIR_PROVISIONING/install_tools.sh "$DIR"

#$DIR_PROVISIONING/composer.sh "$DIR"

#$DIR_PROVISIONING/migrate.sh "$DIR"

$DIR_PROVISIONING/publish.sh "$DIR_PROVISIONING"

echo "Provisioning complete."
#!/bin/bash

DIR_PROVISIONING=$1
DB_NAME=$2
DB_USER=$3

# Install PostgreSQL
# This provisioning is compatible with PostgreSQL v9.x
echo " "
echo "*************************"
echo "* Install PostgreSQL... *"
echo "*************************"

# Install PostgreSQL
apt-get -qq install postgresql postgresql-contrib php5-pgsql

# Get PostgreSQL version
PG_VERSION="ls /etc/postgresql"

# Set config
if [ -f /etc/postgresql/$PG_VERSION/main/postgresql.conf ]; then
    sed -i -e "s/#listen_addresses = 'localhost'/listen_addresses = '*'/" /etc/postgresql/$PG_VERSION/main/postgresql.conf
fi

# Set pg_hba
if [ -f /etc/postgresql/$PG_VERSION/main/pg_hba.conf ];
then
    cp $DIR_PROVISIONING/etc/postgresql/9.x/main/pg_hba.conf /etc/postgresql/$PG_VERSION/main/pg_hba.conf
fi

# Restart service
service postgresql restart

# Create users & databases
su postgres << EOF
psql -c "
  CREATE USER $DB_USER WITH SUPERUSER PASSWORD '$DB_USER';
  "
EOF

su postgres << EOF
psql -c "
  CREATE DATABASE $DB_NAME OWNER $DB_USER;
  "
EOF

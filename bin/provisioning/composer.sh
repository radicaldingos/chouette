#!/bin/bash

DIR=$1

echo " "
echo "**************************"
echo "* Downloading vendors... *"
echo "**************************"
cd $DIR
composer install
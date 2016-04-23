#!/bin/bash

DIR=$1

echo " "
echo "********************"
echo "* DB Migrations... *"
echo "********************"
$DIR/yii migrate/up --connectionID=db --interactive=0
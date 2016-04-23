#!/bin/bash

#!/bin/bash
DIR_PROVISIONING=$1

echo " "
echo "*****************************"
echo "* Virtual hosts creation... *"
echo "*****************************"
if [ -f $DIR_PROVISIONING/etc/apache2/sites-available/chouette ];
then
    cp $DIR_PROVISIONING/etc/apache2/sites-available/chouette /etc/apache2/sites-available/chouette
fi
if [ -f $DIR_PROVISIONING/etc/apache2/sites-available/build ];
then
    cp $DIR_PROVISIONING/etc/apache2/sites-available/build /etc/apache2/sites-available/build
fi

a2ensite chouette
a2ensite build
service apache2 restart

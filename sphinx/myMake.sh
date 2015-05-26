#!/bin/bash
cd $1
make $2
chown -R www-data:www /var/www 
chmod -R a+w /var/www 
echo "Fertig"

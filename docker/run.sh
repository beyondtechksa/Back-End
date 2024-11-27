#!/bin/bash
cd /var/www
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache
/usr/bin/supervisord -c /etc/supervisord.conf

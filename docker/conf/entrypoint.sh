#!/bin/bash 


apachectl start

exec /usr/bin/supervisord -c /etc/supervisor/supervisord.conf
FROM "ubuntu"


RUN apt-get update && apt-get install -y curl vim apache2 supervisor  gpg sqlite3 iputils-ping inetutils-traceroute\ 
   && curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c' | gpg --dearmor | tee /etc/apt/keyrings/ppa_ondrej_php.gpg > /dev/null \
  && echo "deb [signed-by=/etc/apt/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu jammy main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
   && apt-get update && apt-get install -y php8.2 php8.2-sqlite3 php8.2-mbstring php8.2-mysql\
    && curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer 

COPY conf/supervisor.conf /etc/supervisor/conf.d/supervisord.conf

COPY conf/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80 443

ENTRYPOINT [ "/usr/local/bin/entrypoint.sh" ]

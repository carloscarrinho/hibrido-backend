FROM ubuntu:19.10

ENV timezone=America/Sao_Paulo

RUN apt-get update && \
    ln -snf /usr/share/zoneinfo/${timezone} /etc/localtime && echo ${timezone} > /etc/timezone && \
    apt-get install -y apache2 && \
    apt-get install -y git && \
    apt-get install -y php && \ 
    apt-get install -y php-xdebug && \
    apt-get install -y php-mysql && \
    apt-get install -y composer

EXPOSE 80

WORKDIR /var/www/html

ENTRYPOINT /etc/init.d/apache2 start && /bin/bash
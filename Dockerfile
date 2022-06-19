FROM scratch
ADD files/alpine-minirootfs-3.16.0-x86_64.tar.gz /
MAINTAINER Bartosz Michałowski
RUN apk --no-cache --update \
    add apache2 \
    apache2-ssl \
    curl \
    php8-apache2 \
    php8-bcmath \
    php8-bz2 \
    php8-calendar \
    php8-common \
    php8-ctype \
    php8-curl \
    php8-dom \
    php8-gd \
    php8-iconv \
    php8-mbstring \
    php8-mysqli \
    php8-mysqlnd \
    php8-openssl \
    php8-pdo_mysql \
    php8-pdo_pgsql \
    php8-pdo_sqlite \
    php8-phar \
    php8-session \
    php8-xml

ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2

WORKDIR /var/www/localhost/htdocs
COPY  index.php  /var/www/localhost/htdocs
EXPOSE 80

CMD ["/usr/sbin/httpd", "-D", "FOREGROUND"]
RUN touch app_log.log && chmod 775 app_log.log && php index.php


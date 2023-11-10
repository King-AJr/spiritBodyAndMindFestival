FROM php:8.1-fpm

RUN apk add --no-cache nginx curl

RUN mkdir -p /run/nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf

RUN mkdir -p /app
COPY . /app

RUN curl -sS https://getcomposer.org/installer | php --install-dir=/usr/local/bin

RUN --user www-data composer install --no-dev

CMD sh /app/docker/startup.sh

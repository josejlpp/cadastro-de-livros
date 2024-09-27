FROM php:8.2-fpm-alpine3.19

RUN apk add --no-cache linux-headers libzip-dev zip composer tzdata libpng-dev

ENV TZ America/Fortaleza

RUN docker-php-ext-install pdo_mysql mysqli gd zip

RUN apk --update --no-cache add \
    supervisor \
    nginx \
    curl \
    dpkg \
    php82-gd \
    php82-mbstring \
    php82-pdo \
    php82-shmop \
    php82-sockets \
    php82-bcmath \
    php82-curl \
    php82-xml \
    php82-session \
    php82-tokenizer \
    php82-dom \
    php82-fileinfo \
    php82-simplexml \
    php82-xmlreader \
    php82-xmlwriter \
    bash

RUN rm -rf /etc/supervisor.d/ \
    && rm -rf /etc/nginx/conf.d/ /etc/nginx/http.d/ /etc/nginx/sites-enabled/ \
    && rm -rf /usr/local/etc/php-fpm.d/*docker* \
    && rm -rf /usr/local/etc/php-fpm.d/www.conf.default \
    && mkdir -p /run/nginx

# Configurando NGINX
COPY setup/etc/nginx/ /etc/nginx/

# Configurando PHP-FPM
COPY setup/usr/local/etc /usr/local/etc/

# Supervisord
COPY setup/etc/supervisord.conf /etc/supervisord.conf
COPY setup/etc/supervisor.d /etc/supervisor.d/

WORKDIR /var/www/html

EXPOSE 80

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisord.conf"]

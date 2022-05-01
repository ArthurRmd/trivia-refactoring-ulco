FROM php:8.1-cli-alpine

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install -f xdebug-3.1.1 \
    && docker-php-ext-enable xdebug \
    && apk del -f .build-deps

RUN echo "Registering docker.host.ip ..." \
    && echo "$(ip route show default | awk '/default/ {print $3}')" "$(echo "docker.host.ip")" >> /etc/hosts

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=. \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer


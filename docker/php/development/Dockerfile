FROM php:8.3-fpm

ARG UID
ARG GID
ARG USER_NAME
ARG SRC_PATH

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" \
 && apt-get update \
 && apt-get -y install git curl wget grep zip unzip zlib1g-dev libzip-dev libcurl4-openssl-dev \
                       libbrotli-dev libevent-dev libicu-dev libidn11-dev libidn2-0-dev cron

RUN pecl install xdebug-3.3.2 && docker-php-ext-enable xdebug

RUN (crontab -l; echo "* * * * * /usr/local/bin/php ${SRC_PATH}/bin/console app:check") > /var/spool/cron/crontabs/root \
 && chmod 0600 /var/spool/cron/crontabs/root

RUN touch /run/php-fpm.pid \
    && groupadd -g ${UID} ${USER_NAME} \
    && useradd -u ${GID} -ms /bin/bash -g ${USER_NAME} ${USER_NAME} \
    && chown ${USER_NAME}:${USER_NAME} /run/php-fpm.pid

USER ${USER_NAME}

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


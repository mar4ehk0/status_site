FROM php:8.3-cli

ARG SRC_PATH

ENV TZ=Europe/Moscow

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
 && apt-get update \
 && apt-get -y install git curl wget grep zip unzip zlib1g-dev libzip-dev libcurl4-openssl-dev \
                       libbrotli-dev libevent-dev libicu-dev libidn11-dev libidn2-0-dev cron

RUN (crontab -l; echo "* * * * * /usr/local/bin/php ${SRC_PATH}/bin/console app:check") > /var/spool/cron/crontabs/root \
 && chmod 0600 /var/spool/cron/crontabs/root

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./app ${SRC_PATH}

WORKDIR ${SRC_PATH}

RUN rm -rf ${SRC_PATH}/var/*

CMD ["cron", "-f"]


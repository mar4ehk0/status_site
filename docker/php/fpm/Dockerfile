FROM phpdockerio/php:8.3-fpm

ARG UID
ARG GID
ARG USER_NAME

RUN apt-get update \
    && apt-get -y --no-install-recommends install php8.3-xdebug php8.3-intl php8.3-gd php8.3-mysql \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN touch /run/php-fpm.pid \
    && groupadd -g ${UID} ${USER_NAME} \
    && useradd -u ${GID} -ms /bin/bash -g ${USER_NAME} ${USER_NAME} \
    && chown ${USER_NAME}:${USER_NAME} /run/php-fpm.pid

USER ${USER_NAME}


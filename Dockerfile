ARG PHP_VERSION

FROM thecodingmachine/php:${PHP_VERSION}-v4-fpm

ENV PHP_EXTENSION_PGSQL=1 \
    PHP_EXTENSION_PDO_PGSQL=1

USER root

RUN apt update \
    && apt install -y --no-install-recommends libfcgi-bin wget \
    && apt clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN echo "pm.status_path = /status" >> /etc/php/${PHP_VERSION}/fpm/pool.d/zz-docker.conf \
    && wget -O /usr/local/bin/php-fpm-healthcheck \
    https://raw.githubusercontent.com/renatomefi/php-fpm-healthcheck/master/php-fpm-healthcheck \
    && chmod +x /usr/local/bin/php-fpm-healthcheck

USER docker

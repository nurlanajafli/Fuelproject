FROM php:7.4-apache
ARG APP_ENV
RUN apt-get update
RUN apt-get -y install libpq-dev unzip iproute2 git libmagickwand-dev libzip-dev libjpeg-dev libpng-dev libmemcached-dev
# supercronic

RUN apt-get install -y curl

ENV SUPERCRONIC_URL=https://github.com/aptible/supercronic/releases/download/v0.1.12/supercronic-linux-amd64 \
    SUPERCRONIC=supercronic-linux-amd64 \
    SUPERCRONIC_SHA1SUM=048b95b48b708983effb2e5c935a1ef8483d9e3e

RUN curl -fsSLO "$SUPERCRONIC_URL" \
 && echo "${SUPERCRONIC_SHA1SUM}  ${SUPERCRONIC}" | sha1sum -c - \
 && chmod +x "$SUPERCRONIC" \
 && mv "$SUPERCRONIC" "/usr/local/bin/${SUPERCRONIC}" \
 && ln -s "/usr/local/bin/${SUPERCRONIC}" /usr/local/bin/supercronic

COPY .docker/crontab-$APP_ENV /tmp/crontab

# supercronic
RUN docker-php-ext-install pdo_pgsql intl gd zip
COPY .docker/composer-setup.php /tmp
RUN php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
COPY .docker/backend-$APP_ENV.conf /etc/apache2/sites-available/backend-$APP_ENV.conf
COPY .docker/cdn-$APP_ENV.conf /etc/apache2/sites-available/cdn-$APP_ENV.conf
COPY .docker/frontend-$APP_ENV.conf /etc/apache2/sites-available/frontend-$APP_ENV.conf
COPY .docker/garage-$APP_ENV.conf /etc/apache2/sites-available/garage-$APP_ENV.conf
COPY .docker/v1-$APP_ENV.conf /etc/apache2/sites-available/v1-$APP_ENV.conf
RUN a2enmod rewrite
RUN a2ensite backend-$APP_ENV
RUN a2ensite cdn-$APP_ENV
RUN a2ensite frontend-$APP_ENV
RUN a2ensite garage-$APP_ENV
RUN a2ensite v1-$APP_ENV
RUN pecl install memcached
RUN pecl install imagick
RUN pecl install xdebug
RUN pecl install apcu_bc
RUN docker-php-ext-enable memcached imagick xdebug
COPY .docker/php-$APP_ENV.ini $PHP_INI_DIR/conf.d/tms.ini
RUN mkdir -p /root/.ssh
COPY .docker/known_hosts /root/.ssh
COPY .docker/id_ed25519 /root/.ssh
COPY .docker/id_ed25519.pub /root/.ssh
RUN chmod g-r,g-w,g-x,o-r,o-w,o-x /root/.ssh/id_ed25519
RUN mkdir -p /var/www/app
WORKDIR /var/www/app
COPY . .
EXPOSE 80
CMD [".docker/apache_start.sh"]

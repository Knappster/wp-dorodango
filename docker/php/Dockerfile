FROM php:8.4-fpm

RUN set -eux; \
  apt-get update; \
  apt-get install -y --no-install-recommends ghostscript; \
  rm -rf /var/lib/apt/lists/*

# Install PHP extensions
# https://make.wordpress.org/hosting/handbook/handbook/server-environment/#php-extensions
RUN set -ex; \
  apt-get update; \
  apt-get install -y --no-install-recommends \
  libfreetype6-dev \
  libicu-dev \
  libjpeg-dev \
  libmagickwand-dev \
  libpng-dev \
  libwebp-dev \
  libzip-dev; \
  docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp; \
  docker-php-ext-install -j "$(nproc)" \
  bcmath \
  exif \
  gd \
  intl \
  mysqli \
  zip; \
  apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false; \
  rm -rf /var/lib/apt/lists/*;

RUN pecl install imagick xdebug

# Install Wordpress CLI
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && \
  php wp-cli.phar --info && \
  chmod +x wp-cli.phar && \
  mv wp-cli.phar /usr/local/bin/wp

# Set recommended PHP.ini settings
# See https://secure.php.net/manual/en/opcache.installation.php
RUN set -eux; docker-php-ext-enable opcache imagick xdebug;
COPY ./docker/php/error-reporting.ini /usr/local/etc/php/conf.d/error-logging.ini
COPY ./docker/php/opcache-recommended.ini /usr/local/etc/php/conf.d/opcache-recommended.ini
COPY ./docker/php/uploads.ini /usr/local/etc/php/conf.d/uploads.ini
COPY ./docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Set user for local perms
RUN addgroup --system www --gid 1000 && \
  adduser --system www --uid 1000 --ingroup www --no-create-home

USER www

WORKDIR /var/www/html
VOLUME /var/www/html

CMD ["php-fpm"]
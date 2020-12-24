FROM php:7.3-fpm-alpine

RUN apk add --no-cache --virtual .persistent-deps \
        nano \
		icu-libs \
		supervisor \
        tzdata \
    && set -eux \
	&& apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		icu-dev \
		build-base \
	&& docker-php-ext-install \
		bcmath \
		pdo_mysql \
		sockets \
	&& ln -s $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini \
    && apk --no-cache del .build-deps

COPY docker/php/php-fpm.d/www.conf /usr/local/etc/php-fpm.d
COPY docker/php/conf.d/laravel.ini $PHP_INI_DIR/conf.d/laravel.ini
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY docker/php/docker-entrypoint.sh /usr/local/bin/docker-app-entrypoint

WORKDIR /srv/app/
ENTRYPOINT ["docker-app-entrypoint"]
CMD ["php-fpm", "-F"]

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER 1

COPY ./app .

RUN composer install --prefer-dist --no-dev --no-scripts --no-progress --no-interaction \
    && composer clear-cache \
    && composer dumpautoload -o \
    && chown -R www-data storage \
    && chown -R www-data tests \

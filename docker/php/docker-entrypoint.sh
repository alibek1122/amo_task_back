#!/bin/sh

set -e

if [ "${1#-}" != "$1" ]; then
        set -- php-fpm "$@"
fi

if [ "$1" = "php-fpm" ] || [ "$1" == "artisan" ]; then
  # The first time volumes are mounted, the project needs to be recreated
    if [ ! -f composer.json ]; then
        composer create-project "laravel/laravel 7.*" tmp --prefer-dist --no-progress --no-interaction
        cp -Rp tmp/. .
        rm -Rf tmp/
    elif [ "$APP_ENV" != 'production' ]; then
        # Always try to reinstall deps when not in prod
        composer install --prefer-dist --no-progress --no-suggest --no-interaction
    fi
fi

crond

supervisord --configuration /etc/supervisord.conf

exec docker-php-entrypoint "$@"

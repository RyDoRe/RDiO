FROM composer as build-stage

RUN composer global require hirak/prestissimo

WORKDIR /var/www

COPY . .

RUN composer install --no-scripts --prefer-dist --no-suggest --no-dev

FROM php:7.2-fpm-alpine

RUN docker-php-ext-install mbstring tokenizer mysqli pdo_mysql

WORKDIR /var/www

COPY --from=build-stage /var/www/ /var/www

RUN php -r "file_exists('.env') || copy('.env.docker', '.env');"
RUN php artisan key:generate

RUN chmod -R 777 storage

ADD .docker/wait-for-it.sh ./

WORKDIR /var/www

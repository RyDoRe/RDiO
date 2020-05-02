FROM composer as build-stage

RUN composer global require hirak/prestissimo

WORKDIR /var/www

COPY packages/api/ /var/www

RUN composer install --no-scripts --prefer-dist --no-suggest --no-dev

FROM php:7.2-fpm-alpine

RUN docker-php-ext-install mbstring tokenizer mysqli pdo_mysql

WORKDIR /var/www

COPY --from=build-stage /var/www/ /var/www

RUN php -r "file_exists('.env') || copy('.env.docker', '.env');"
RUN php artisan key:generate

RUN chmod -R 777 storage

ADD infrastructure/api/wait-for-it.sh ./

RUN apk add make build-base lame-dev libxml2-dev libshout-dev libvorbis-dev

WORKDIR /tmp

RUN wget http://downloads.us.xiph.org/releases/ices/ices-0.4.tar.gz
RUN tar xf ices-0.4.tar.gz

WORKDIR /tmp/ices-0.4

RUN ./configure --prefix=/usr/local --with-pic --with-lame
RUN make
RUN make install

WORKDIR /var/www

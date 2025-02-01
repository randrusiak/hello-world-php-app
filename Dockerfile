FROM php:8.1-apache

RUN pecl install redis && docker-php-ext-enable redis && a2enmod rewrite

COPY src/ /var/www/html/

RUN chown -R www-data:www-data /var/www/html/

USER www-data

EXPOSE 80

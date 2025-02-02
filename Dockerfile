FROM php:8.1-apache

ENV APACHE_LISTEN_PORT=8080

RUN pecl install redis && docker-php-ext-enable redis && a2enmod rewrite && \
  sed -i "s/Listen 80/Listen ${APACHE_LISTEN_PORT}/" /etc/apache2/ports.conf && \
  sed -i "s/:80/:${APACHE_LISTEN_PORT}/" /etc/apache2/sites-available/000-default.conf

COPY src/ /var/www/html/

RUN chown -R www-data:www-data /var/www/html/

USER www-data

EXPOSE ${APACHE_LISTEN_PORT}

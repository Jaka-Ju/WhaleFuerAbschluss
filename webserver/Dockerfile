# Basisimage mit Apache + PHP
FROM php:8.2-apache

# Arbeitsverzeichnis
WORKDIR /var/www/html

# PHP/HTML-Dateien ins Image kopieren
COPY html/ /var/www/html/

# Apache-Port 80 ist bereits exposed
RUN chown -R www-data:www-data /var/www/html/
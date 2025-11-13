FROM php:8.2-apache

# 2. Installiere die mysqli-Erweiterung (das ist der "Befehl")
RUN docker-php-ext-install mysqli

# 3. (Optional, aber empfohlen) Aktiviere mod_rewrite für Apache
RUN a2enmod rewrite
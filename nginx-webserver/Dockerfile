FROM php:8.2-fpm

#nginx installieren und anschliessend paket liste deleten für kleines image
RUN apt update && \ apt install -y nginx && \ rm -rf /var/lib/apt/lists/*

WORKDIR /usr/share/nginx/html
CMD ["sh", "-c", "php-fpm8.2 -F & nginx -g 'daemon off;'"] 
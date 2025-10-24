FROM php:8.2-fpm

#nginx installieren und anschliessend paket liste deleten für kleines image
RUN apt update 
RUN apt install -y nginx   
RUN rm -rf /var/lib/apt/lists/*

WORKDIR /usr/share/nginx/html
CMD ["sh", "-c", "php-fpm8.2 -F & nginx -g 'daemon off;'"] 
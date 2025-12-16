FROM php:8.2-apache

# Instalamos extensiones
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo_mysql

# 1. Activamos el módulo SSL de Apache y el sitio por defecto SSL
RUN a2enmod ssl && a2ensite default-ssl

# 2. Copiamos tus certificados al lugar donde Apache los espera
COPY web.crt /etc/ssl/certs/ssl-cert-snakeoil.pem
COPY web.key /etc/ssl/private/ssl-cert-snakeoil.key

# Copiamos la web
COPY . /var/www/html/

# 3. Exponemos TAMBIÉN el puerto 443
EXPOSE 80 443
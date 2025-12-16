FROM php:8.2-apache

# Instalamos extensiones
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo_mysql

# Activamos el módulo SSL de Apache y el sitio por defecto SSL
RUN a2enmod ssl && a2ensite default-ssl

# Usar la configuración de producción (oculta errores, optimiza rendimiento)
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Copiamos los certificados al lugar donde Apache los espera
COPY certs/web.crt /etc/ssl/certs/ssl-cert-snakeoil.pem
COPY certs/web.key /etc/ssl/private/ssl-cert-snakeoil.key

# Copiamos la web
COPY . /var/www/html/

# 3. Exponemos los puertos
EXPOSE 80 443
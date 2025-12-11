FROM php:8.2-apache

# Instalamos las extensiones de PHP necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo_mysql

# Copiamos los archivos de la aplicación al directorio de trabajo de Apache
COPY . /var/www/html/

# Exponemos el puerto 80 para Apache
EXPOSE 80

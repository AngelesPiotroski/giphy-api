FROM php:8.2-apache

# Instala dependencias del sistema
RUN apt-get update && apt-get install -y \
    git zip unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Habilita módulos de Apache
RUN a2enmod rewrite

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia el contenido del proyecto al contenedor
COPY . /var/www/html

# Instala dependencias de PHP
RUN composer install --no-interaction --no-progress --prefer-dist

# Da permisos a Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
    
# RUTAS para configuración
COPY ./docker/apache2/000-default.conf /etc/apache2/sites-available/000-default.conf

# Expone el puerto de Apache
EXPOSE 80

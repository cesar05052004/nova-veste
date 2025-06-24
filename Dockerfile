FROM php:8.2-apache

# Instala extensiones necesarias
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql zip

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia el proyecto Laravel
COPY . /var/www/html

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Da permisos correctos
RUN chmod -R 777 storage bootstrap/cache

# Instala dependencias PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Activa Apache mod_rewrite
RUN a2enmod rewrite

# Configura Apache
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]

FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libzip-dev zip unzip libicu-dev libpng-dev git libonig-dev \
    && docker-php-ext-install pdo_mysql mysqli intl \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Default command: start apache
CMD ["apache2-foreground"]

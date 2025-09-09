# PHP 8.4 com Apache (sem FPM)
FROM php:8.4-apache

# Dependências p/ compilar extensões comuns do Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# Install common PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    mysqli \
    gd \
    intl \
    zip

# Servir /public como DocumentRoot
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
 && sed -ri 's!<Directory /var/www/>!<Directory /var/www/>\\n    AllowOverride All!g' /etc/apache2/apache2.conf

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# (Opcional) Ajustes PHP
RUN { \
  echo "date.timezone=America/Fortaleza"; \
  echo "memory_limit=512M"; \
  echo "upload_max_filesize=64M"; \
  echo "post_max_size=64M"; \
} > /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html

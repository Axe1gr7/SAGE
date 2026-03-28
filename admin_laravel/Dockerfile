# Usar la imagen oficial de PHP
FROM php:8.4-cli

# Crear carpeta de trabajo 
WORKDIR /var/www

# Instalar dependencias del sistema, herramientas y librerías para PostgreSQL
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    libzip-dev \
    libpq-dev  # <-- ¡NUEVO! Vital para que PHP pueda hablar con PostgreSQL

# Instalar extensiones de PHP (Cambié pdo_mysql por pdo_pgsql)
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip

# Copiar Composer de la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar el código del proyecto al contenedor
COPY . .

# Instalar las dependencias de Laravel 
RUN composer install --no-interaction --optimize-autoloader

# Asegurar que las carpetas existan y dar permisos de escritura (Vital para Laravel)
RUN mkdir -p storage bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Exponer el puerto interno en el que trabajará Artisan
EXPOSE 8000

# Comando para ejecutar Laravel
CMD ["php", "artisan", "serve", "--host", "0.0.0.0", "--port", "8000"]
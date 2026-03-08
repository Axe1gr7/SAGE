FROM php:8.4-cli

# Crear carpeta de trabajo 
WORKDIR /var/www

# Instalar dependencias del sistema y herramientas de PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    libzip-dev

# Instalar extensiones de PHP necesarias para Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Copiar Composer de la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar el código del proyecto al contenedor
COPY . .

# Instalar las dependencias de Laravel (Equivalente a pip install -r requirements.txt)
RUN composer install --no-interaction --optimize-autoloader

# Asegurar que las carpetas existan y dar permisos de escritura (Vital para Laravel)
RUN mkdir -p storage bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Exponer el puerto interno en el que trabajará Artisan
EXPOSE 8000

# Comando para ejecutar Laravel
CMD ["php", "artisan", "serve", "--host", "0.0.0.0", "--port", "8000"]
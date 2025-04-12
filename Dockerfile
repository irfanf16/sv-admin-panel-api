FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
        vim \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libwebp-dev \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        unzip \
        git \
        libzip-dev \
        nano \
    && docker-php-ext-install -j$(nproc) iconv gd \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd calendar soap

# Install PHP zip extension
RUN docker-php-ext-install zip

# Now configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-webp --with-jpeg && \
    docker-php-ext-install gd

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions including mbstring
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd calendar soap

# Download and Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Rename migrations.txt to migrations.sh, and ensure it has full permissions
RUN mv migrations.txt migrations.sh && chmod +x /var/www/migrations.sh

# Use an absolute path to apply permissions explicitly
RUN ls -l /var/www/migrations.sh

# Make sure the directories for Laravel's storage and cache exist and are writable
RUN mkdir -p storage bootstrap/cache \
    && mkdir -p storage/framework/cache data storage/framework/sessions storage/framework/views bootstrap/cache \
    && find . -type d -exec chmod 755 {} \; \
    && find . -type f -exec chmod 644 {} \; \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache \
    && chown -R www-data:www-data public

RUN rm -rf composer.lock
RUN composer install --no-interaction --prefer-dist

RUN php artisan config:clear && php artisan view:clear && php artisan route:cache && php artisan queue:restart && php artisan storage:link
RUN echo "memory_limit = 1020M" >> /usr/local/etc/php/conf.d/memory-limit.ini
RUN echo "max_input_vars = 30000" >> /usr/local/etc/php/php.ini
RUN echo "post_max_size = 100M" >> /usr/local/etc/php/php.ini
RUN echo "upload_max_filesize = 100M" >> /usr/local/etc/php/php.ini
RUN echo "max_execution_time = 3000" >> /usr/local/etc/php/php.ini

# Use ENTRYPOINT to run the migrations.sh file and start the application
ENTRYPOINT ["/bin/sh", "-c", "chmod +x /var/www/migrations.sh && /bin/sh /var/www/migrations.sh && php artisan serve --host=0.0.0.0 && php artisan storage:link && php artisan schedule:work && php artisan queue:work database --queue=emails && php artisan queue:work"]

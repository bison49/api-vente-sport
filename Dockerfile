# Use the official PHP image as a base
FROM php:8.2-apache

# Install required packages
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Install required PHP axtensions
RUN docker-php-ext-install \
    intl \
    pdo_mysql \
    zip \
    opcache

# Enable Apache modules
RUN a2enmod rewrite headers

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory
WORKDIR /var/www/html

# Copy the application code to the container
COPY . .

# Install dependencies
RUN composer install --no-scripts --no-interaction --prefer-dist

# Expose port 8000 for traffic
EXPOSE 8000

# Set the entrypoint script
ENTRYPOINT [ "apache2-foreground" ]

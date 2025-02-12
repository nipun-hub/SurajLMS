# Use PHP 8.1 with Apache
FROM php:8.1.25-apache

# Install MySQLi and PDO extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Set PHP configurations
RUN echo "output_buffering = On" >> /usr/local/etc/php/php.ini \
    && echo "display_errors = Off" >> /usr/local/etc/php/php.ini \
    && echo "log_errors = On" >> /usr/local/etc/php/php.ini \
    && echo "error_log = /var/log/php/error.log" >> /usr/local/etc/php/php.ini

# Copy project files
COPY ./www /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Create log directory and set permissions
RUN mkdir -p /var/log/php \
    && touch /var/log/php/error.log \
    && chmod -R 777 /var/log/php

# Expose Apache port
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]

# Use official PHP 8.3 image with Apache
FROM php:7.4-apache

# Enable URL rewriting for CodeIgniter
RUN a2enmod rewrite

# Install mysqli and pdo_mysql extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy your application code into the container
COPY . /var/www/html/

# Set permissions (optional)
RUN chown -R www-data:www-data /var/www/html/

# Expose port 80 (Render maps it automatically)
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
FROM php:8.2-apache

# QR कोड और इमेज जनरेशन के लिए आवश्यक GD लाइब्रेरी इंस्टॉल करना
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -y gd mysqli pdo pdo_mysql

COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html/

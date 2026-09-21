FROM php:8.2-apache

# सिस्टम डिपेंडेंसीज इंस्टॉल करना
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mysqli pdo pdo_mysql

# प्रोजेक्ट की सभी फाइलों को कंटेनर में कॉपी करना
COPY . /var/www/html/

# सही परमिशन्स सेट करना ताकि फाइल अपलोड और QR कोड सही से काम करें
RUN chown -R www-data:www-data /var/www/html/

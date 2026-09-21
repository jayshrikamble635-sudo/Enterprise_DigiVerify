FROM php:8.2-apache

# 1. अपाचे के रीराइट मोड को ऑन करना (ताकि CSS/JS सही से लोड हों)
RUN a2enmod rewrite

# 2. आवश्यक लाइब्रेरी और MySQL एक्सटेंशन इंस्टॉल करना
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mysqli pdo pdo_mysql

# 3. पूरे प्रोजेक्ट की फाइलों को कॉपी करना
COPY . /var/www/html/

# 4. सही परमिशन्स देना
RUN chown -R www-data:www-data /var/www/html/

EXPOSE 80

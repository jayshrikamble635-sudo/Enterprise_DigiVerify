FROM php:8.2-apache

# 1. QR कोड, इमेज जनरेशन (GD) और MySQL के लिए आवश्यक डिपेंडेंसीज इंस्टॉल करना
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mysqli pdo pdo_mysql

# 2. पूरे प्रोजेक्ट को डिफ़ॉल्ट अपाचे डायरेक्टरी में कॉपी करना
COPY . /var/www/html/

# 3. परमिशन्स सेट करना
RUN chown -R www-data:www-data /var/www/html/

# 4. अपाचे के डिफ़ॉल्ट पोर्ट को ओपन रखना
EXPOSE 80

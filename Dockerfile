FROM php:7.1.0-apache

#ENV APACHE_DOCUMENT_ROOT /var/www/html
ENV APACHE_DOCUMENT_ROOT /app/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN a2enmod rewrite
RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get clean && apt-get -y update && apt-get install -y locales git
RUN printf 'en_US.UTF-8 UTF-8\n' >> /etc/locale.gen
RUN printf 'fr_FR.UTF-8 UTF-8\n' >> /etc/locale.gen
RUN locale-gen en_US.UTF-8 fr_FR.UTF-8

WORKDIR /app
RUN apt-get update && apt-get install -y \
		libfreetype6-dev \
		libjpeg62-turbo-dev \
		libmcrypt-dev \
		libpng-dev \
	&& docker-php-ext-install -j$(nproc) iconv mcrypt \
	&& docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
	&& docker-php-ext-install -j$(nproc) gd
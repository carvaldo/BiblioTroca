# Imagem base com PHP 8.3 Composer, NPM e outras ferramentas.

FROM php:8.3-cli

# # Instala extensões comuns
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    libmagickwand-dev \
    zip \
    unzip \
    curl \
    nano \
    btop \
    iputils-ping \
    gsfonts \
    wget \
    npm \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        calendar

# Define locale como UTF-8
RUN apt-get install -y locales \
    && sed -i '/pt_BR.UTF-8/s/^# //g' /etc/locale.gen \
    && locale-gen pt_BR.UTF-8

ENV LANG=pt_BR.UTF-8
ENV LANGUAGE=pt_BR:pt
ENV LC_ALL=pt_BR.UTF-8

# Instala a extensão Imagick via PECL
RUN pecl install imagick && docker-php-ext-enable imagick

# Instala o Xdebug via PECL
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configuração básica do Xdebug
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/xdebug.ini

# Instala o Composer 2
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Laravel installer
RUN composer global require laravel/installer
RUN echo 'export PATH="$PATH:$HOME/.config/composer/vendor/bin"' >> ~/.bashrc

EXPOSE 8000 9003

# Define diretório de trabalho
WORKDIR /var/www/html
FROM php:7.1-fpm
MAINTAINER Yura Tselischev <y.tselischev@gmail.com>

RUN apt-get update && apt-get install -y \
    openssl \
    git \
    libzip-dev \
    zip \
    vim \
    curl \
    unzip
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure zip --with-libzip \
&& docker-php-ext-install pdo pdo_mysql zip

# Install Composer
RUN echo "$(curl -sS https://composer.github.io/installer.sig) -" > composer-setup.php.sig \
        && curl -sS https://getcomposer.org/installer | tee composer-setup.php | sha384sum -c composer-setup.php.sig \
        && php composer-setup.php && rm composer-setup.php* \
        && chmod +x composer.phar && mv composer.phar /usr/bin/composer

ADD docker/php71/custom.ini /usr/local/etc/php/conf.d/

WORKDIR /var/www
CMD ["php-fpm"]
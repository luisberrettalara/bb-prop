FROM php:7.2-apache

WORKDIR /var/www

RUN apt-get update && \
    apt-get install -y \
        zlib1g-dev \
        curl \
        gnupg \
        git \
        libpng-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        cron \
        python python-httplib2 python-m2crypto python-pip \
        locales

COPY ssl/ /etc/apache2/ssl/
COPY vhosts/ /etc/apache2/sites-enabled/

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install mysqli pdo pdo_mysql zip mbstring gd
RUN a2enmod rewrite
RUN a2enmod ssl
RUN service apache2 restart

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --filename=composer --install-dir=/usr/local/bin
RUN php -r "unlink('composer-setup.php');"

RUN (crontab -l ; echo "* * * * * php /var/www/lanueva/artisan schedule:run >> /dev/null 2>&1") | crontab
RUN cron

RUN git clone --single-branch --branch master https://github.com/joarobles/pyafipws
RUN cd pyafipws && pip install -r requirements.txt

COPY pyafipws/ /var/www/pyafipws/

RUN echo "America/Argentina" > /etc/timezone && \
    dpkg-reconfigure -f noninteractive tzdata && \
    sed -i -e 's/# en_US.UTF-8 UTF-8/en_US.UTF-8 UTF-8/' /etc/locale.gen && \
    sed -i -e 's/# es_AR.UTF-8 UTF-8/es_AR.UTF-8 UTF-8/' /etc/locale.gen && \
    echo 'LANG="es_AR.UTF-8"'>/etc/default/locale && \
    dpkg-reconfigure --frontend=noninteractive locales && \
    update-locale LANG=es_AR.UTF-8

ENV LANG es_AR.UTF-8
ENV LANGUAGE es_AR.UTF-8
ENV LC_ALL es_AR.UTF-8

RUN chown -R www-data /var/www/pyafipws
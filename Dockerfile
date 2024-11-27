FROM php:8.3-fpm

# Set the working directory
WORKDIR /var/www


# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    gnupg2 \
    lsb-release\
    curl\
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    git \
    lua-zlib-dev \
    nginx \
    supervisor \
    libpango-1.0-0 \
    libharfbuzz0b \
    libpangoft2-1.0-0 \
    libzip-dev \
    libonig-dev \
    libicu-dev \
    libmagickwand-dev \
    sudo 

# Import the official Nginx signing key (if needed)
RUN curl https://nginx.org/keys/nginx_signing.key | gpg --dearmor \
    | tee /usr/share/keyrings/nginx-archive-keyring.gpg >/dev/null

# Set up the Nginx repository
RUN echo "deb [signed-by=/usr/share/keyrings/nginx-archive-keyring.gpg] \
http://nginx.org/packages/debian `lsb_release -cs` nginx" \
    | tee /etc/apt/sources.list.d/nginx.list

# Update apt, install Nginx, and check the version
RUN apt-get update && apt-get install -y nginx
## Set up Node.js
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Clean up
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg
RUN pecl install redis
RUN docker-php-ext-enable  redis
RUN docker-php-ext-install  mbstring  pdo_mysql zip exif pcntl gd  intl  bcmath  opcache xml dom 
  

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


#RUN adduser www-data sudo
RUN echo '%sudo ALL=(ALL) NOPASSWD:/usr/sbin/nginx' >> /etc/sudoers
RUN touch /var/log/supervisord.log && touch /var/log/nginx/php-error.log && touch /var/log/nginx/php-access.log && mkdir /var/log/php-fpm && touch /var/log/php-fpm/www.log.slow && chown www-data: /var/log/php-fpm/www.log.slow  && touch /var/log/php-fpm/www.access.log && chown www-data: /var/log/php-fpm/www.access.log && touch /var/log/nginx/error.log && touch /var/log/nginx/access.log && chown www-data: -R /var/log/nginx/ && chown www-data /var/log/supervisord.log  && chown www-data: /usr/local/sbin/php-fpm && chown www-data: -R /usr/local/etc && touch /var/run/supervisord.pid && chown www-data: /var/run/supervisord.pid 


# Copy code to /var/www
COPY  . /var/www

RUN chmod -R 775 /var/www/storage

# Copy nginx/php/supervisor configs
RUN cp docker/supervisord.conf /etc/supervisord.conf
RUN cp docker/php.ini /usr/local/etc/php/conf.d/app.ini
RUN cp docker/nginx.conf /etc/nginx/sites-enabled/default
RUN cp docker/www.conf /usr/local/etc/php-fpm.d/www.conf
RUN cp docker/main.conf /etc/nginx/nginx.conf
RUN chown -R www-data:www-data /var/www
USER www-data
# Deployment steps ....
RUN composer update lcobucci/jwt
RUN composer install --optimize-autoloader --no-dev
RUN npm i
RUN npm run build
RUN chmod +x /var/www/docker/run.sh

EXPOSE 80
ENTRYPOINT ["/var/www/docker/run.sh"]

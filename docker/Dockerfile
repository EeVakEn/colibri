FROM php:8.3-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid
ARG NODE_VERSION=18

RUN curl -sL https://deb.nodesource.com/setup_$NODE_VERSION.x | bash -
# Install system dependencies
RUN apt-get update && apt-get install -y \
    vim \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    zip \
    cron \
    unzip \
    nodejs \
    poppler-utils \
    ghostscript \
    libzip-dev \
    zlib1g-dev \
    && export LIBZIP_CFLAGS="-I/usr/include" LIBZIP_LIBS="-L/usr/lib -lzip"


RUN mkdir -p /mnt/storage

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Get latest Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Media libraries
RUN apt-get update && apt-get install -y \
    libjpeg-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    libfreetype6-dev \
    libxslt-dev \
    libpng-dev \
    libmagickwand-dev \
    --no-install-recommends \
    && rm -rf /var/lib/apt/lists/*

# Compile Imagick extension
RUN pecl bundle -d /usr/src/php/ext imagick \
    && docker-php-ext-configure imagick --with-libdir=/usr/include \
    && docker-php-ext-install -j$(nproc) imagick

# Install PHP extensions
RUN docker-php-ext-configure gd --with-webp --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) pdo_mysql mbstring exif pcntl bcmath gd zip xml xsl


# Set working directory
WORKDIR /var/www/html

RUN echo "memory_limit=2048M" >> /usr/local/etc/php/php.ini
RUN echo "upload_max_filesize = 700M" >> /usr/local/etc/php/php.ini
USER $user

FROM dunglas/frankenphp:1-php8.4

ENV SERVER_NAME=:80

# System deps for PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq-dev \
        libicu-dev \
        libzip-dev \
        unzip \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions required by Symfony + Doctrine (pgsql)
RUN docker-php-ext-configure intl \
    && docker-php-ext-install -j"$(nproc)" \
        intl \
        opcache \
        pdo_pgsql \
        zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Recommended PHP settings for Symfony
RUN { \
        echo "opcache.memory_consumption=256"; \
        echo "opcache.max_accelerated_files=20000"; \
        echo "realpath_cache_size=4096K"; \
        echo "realpath_cache_ttl=600"; \
    } > "$PHP_INI_DIR/conf.d/symfony.ini"

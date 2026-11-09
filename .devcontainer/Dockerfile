FROM mcr.microsoft.com/devcontainers/base:ubuntu-24.04

# PHP 7.4 と 8.3 を両方入れ、php-switch で切り替える
RUN apt-get update \
 && apt-get install -y --no-install-recommends software-properties-common sqlite3 unzip \
 && add-apt-repository -y ppa:ondrej/php \
 && apt-get update \
 && for v in 7.4 8.3; do \
      apt-get install -y --no-install-recommends \
        php$v-cli php$v-intl php$v-mbstring php$v-sqlite3 php$v-xml php$v-zip php$v-gd php$v-curl; \
    done \
 && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY php-switch /usr/local/bin/php-switch
RUN chmod +x /usr/local/bin/php-switch && php-switch 7.4

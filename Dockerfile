FROM php:7.4-cli

WORKDIR /opt/service

COPY app/Core ./app/Core
COPY app/Providers ./app/Providers
COPY app/Http ./app/Http
COPY config ./config
COPY database/migrations ./database/migrations
COPY app/functions.php ./app
COPY openapi.json .
COPY bootstrap.php .
COPY composer.json .
COPY composer.lock .

RUN curl --silent --show-error https://getcomposer.org/installer | php && \
   php composer.phar self-update --preview && \
   php composer.phar install --no-dev

CMD ["php", "/opt/service/app/Http/main.php"]
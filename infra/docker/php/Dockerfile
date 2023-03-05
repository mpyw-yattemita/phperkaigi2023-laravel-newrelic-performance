FROM golang:1.19 as phoo_build

RUN apt update -y
RUN go install github.com/alash3al/phoo@latest

FROM php:8.1-fpm-bullseye AS php_fpm_customized

WORKDIR /workspace

# 環境変数
ENV TZ=UTC \
  # locale
  LANG=en_US.UTF-8 \
  LANGUAGE=en_US:en \
  LC_ALL=en_US.UTF-8 \
  # composer environment
  COMPOSER_ALLOW_SUPERUSER=1 \
  COMPOSER_HOME=/composer

# phoo
COPY --from=phoo_build /go/bin/phoo /usr/bin/phoo

# Composer
COPY --from=composer:2.4 /usr/bin/composer /usr/bin/composer

# 一般的な依存関係
RUN apt-get update \
  && apt-get -y install --no-install-recommends \
    locales \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    procps \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* \
  && locale-gen en_US.UTF-8 \
  && localedef -f UTF-8 -i en_US en_US.UTF-8 \
  && docker-php-ext-install \
    intl \
    pdo_mysql \
    zip \
    bcmath \
    opcache \
  && docker-php-ext-configure opcache --enable-opcache \
  && composer config -g process-timeout 3600 \
  && composer config -g repos.packagist composer https://packagist.org

# New Relic Agent インストール
ARG NEW_RELIC_AGENT_VERSION
RUN curl -L https://download.newrelic.com/php_agent/archive/${NEW_RELIC_AGENT_VERSION}/newrelic-php5-${NEW_RELIC_AGENT_VERSION}-linux.tar.gz | tar -C /tmp -zx \
    && NR_INSTALL_USE_CP_NOT_LN=1 NR_INSTALL_SILENT=1 /tmp/newrelic-php5-${NEW_RELIC_AGENT_VERSION}-linux/newrelic-install install \
    && rm -rf /tmp/newrelic-php5-* /tmp/nrinstall*

FROM php_fpm_customized AS php_fpm_customized_with_xdebug

# XDebug インストール
# （zend_extension ディレクティブで xdebug.so を読み込まない限り無効）
RUN pecl install xdebug

FROM php_fpm_customized AS backend_build

# バックエンドのパッケージインストール
COPY ./src/composer.json ./src/composer.lock /workspace/
RUN composer install --working-dir=/workspace --no-scripts --no-autoloader --no-dev

FROM node:19.7 as frontend_build

# フロントエンドのパッケージインストールとビルド
ARG VITE_GITHUB_URL
ARG VITE_NEW_RELIC_URL
WORKDIR /workspace
RUN mkdir -p /workspace/public
COPY ./src/package.json ./src/package-lock.json ./src/tsconfig.json ./src/vite.config.ts /workspace/
COPY ./src/resources/ts/ /workspace/resources/ts/
COPY ./src/resources/css/ /workspace/resources/css/
RUN npm ci && npm run prod

FROM php_fpm_customized AS bundled

# 設定ファイルの設置
COPY ./infra/docker/php/php.ini $PHP_INI_DIR/conf.d/zzz-php.ini
COPY ./infra/docker/php/www.conf /usr/local/etc/php-fpm.d/zzz-www.conf
COPY ./infra/docker/php/php-ini.d/php.cloud.ini $PHP_INI_DIR/conf.d/zzzz-php.custom.ini
COPY ./infra/docker/php/php-fpm.d/www.cloud.conf /usr/local/etc/php-fpm.d/zzzz-www.custom.conf

# デプロイ用エントリポイントの作成
COPY ./infra/docker/php/docker-php-entrypoint-cloud /usr/local/bin/
ENTRYPOINT ["docker-php-entrypoint-cloud"]

# ソースファイルの設置
COPY ./src/ /workspace/
COPY --from=backend_build /workspace/vendor/ /workspace/vendor/
COPY --from=frontend_build /workspace/public/js/ /workspace/public/js/
COPY --from=frontend_build /workspace/public/css/ /workspace/public/css/
RUN set -eux \
    && composer dump-autoload --working-dir=/workspace --no-scripts \
    && chmod -R a=rwX /workspace/storage /workspace/bootstrap/cache

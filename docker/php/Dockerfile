FROM debian:stretch-slim

RUN export LC_ALL=C.UTF-8

RUN apt-get -y update

RUN apt-get install -y --no-install-recommends \
    bash \
    wget \
    apt-transport-https \
    ca-certificates

RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
RUN echo "deb https://packages.sury.org/php/ stretch main" > /etc/apt/sources.list.d/php-sury.list

RUN apt-get -y update && \
    apt-get install -y --no-install-recommends \
    git-core \
    nano \
    libssl-dev \
    curl \
    gettext \
    openssl \
    imagemagick \
    gcc \
    libpcre3-dev \
    make \
    re2c \
    gnupg2


RUN apt-get -y update && \
    apt-get install -y --no-install-recommends \
    php7.2 \
    php7.2-dev \
    php7.2-common \
    php7.2-fpm \
    php7.2-pdo \
    php7.2-mysqli \
    php7.2-bcmath \
    php7.2-cli \
    php7.2-iconv \
    php7.2-curl \
    php7.2-mbstring \
    php7.2-ctype \
    php7.2-gd \
    php7.2-imagick \
    php7.2-intl \
    php7.2-xml \
    php7.2-iconv \
    php7.2-json \
    php-redis \
    php7.2-tokenizer \
    php7.2-simplexml \
    php7.2-opcache

RUN git config --global user.email "nikolas.serko@gmail.com" \
    && git config --global user.name "Nikolas Serko" \
    && git config --global push.default current

# Install Phalcon v3.2
RUN curl -s https://packagecloud.io/install/repositories/phalcon/stable/script.deb.sh | bash
RUN apt-get install -y php7.2-phalcon
#RUN git clone https://github.com/phalcon/cphalcon.git --branch 3.2.x && \
#    cd cphalcon/build && ./install

#COPY phalcon.ini /etc/php/7.1/mods-available/

#WORKDIR /etc/php/7.0
#RUN ln -s /etc/php/7.1/mods-available/phalcon.ini /etc/php/7.1/cli/conf.d/25-phalcon.ini
#RUN ln -s /etc/php/7.1/mods-available/phalcon.ini /etc/php/7.1/fpm/conf.d/25-phalcon.ini

COPY www.conf /etc/php/7.2/fpm/pool.d/
COPY php-fpm.conf /etc/php/7.2/fpm/
COPY php.ini /etc/php/7.2/fpm/

# Install composer global bin
RUN wget -qO- https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    chmod +x /usr/local/bin/composer

# Install NodeJS
RUN curl -sL https://deb.nodesource.com/setup_9.x | bash -
RUN apt-get install -y nodejs

# install Yarn
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - && \
    echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list

RUN apt-get update && apt-get install yarn

RUN rm -rf /var/lib/apt/lists/*

RUN useradd -ms /bin/bash  -u 1111 yona
RUN usermod -aG www-data yona

RUN mkdir /var/run/php && \
    chown yona:www-data /run/php/

#RUN chown www-data:www-data /var/log/php7.2-fpm.log

WORKDIR /var/www/yona

USER yona

EXPOSE 9000

ENTRYPOINT ["/usr/sbin/php-fpm7.2", "-F"]
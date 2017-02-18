FROM debian:jessie

RUN export LC_ALL=C.UTF-8

RUN apt-get update && apt-get -y install \
    wget \
    sudo \
    curl \
    git-core \
    zip

RUN git config --global user.email "webtorua@gmail.com" \
     && git config --global user.name "Oleksandr Torosh" \
     && git config --global push.default current

RUN wget https://www.dotdeb.org/dotdeb.gpg && apt-key add dotdeb.gpg

RUN echo 'deb http://packages.dotdeb.org jessie all' >> /etc/apt/sources.list && \
    echo 'deb-src http://packages.dotdeb.org jessie all' >> /etc/apt/sources.list

RUN apt-get update && apt-get -y install php7.0-fpm php7.0-mysql php7.0-curl php7.0-redis php7.0-gd php7.0-intl

# Install composer global bin
RUN wget -qO- https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    chmod +x /usr/local/bin/composer

RUN curl -s https://packagecloud.io/install/repositories/phalcon/stable/script.deb.sh | sudo bash

# Ubuntu 16.04+ or Debian 9+
RUN sudo apt-get install php7.0-phalcon

RUN mkdir -p /yona-cms
WORKDIR /yona-cms

EXPOSE 9000

ENTRYPOINT ["/usr/sbin/php-fpm7.0", "-FO"]
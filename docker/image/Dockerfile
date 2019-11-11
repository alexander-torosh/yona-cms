FROM debian:buster

RUN apt update
RUN apt install -y sudo wget curl git-core

# PHP 7.3
RUN apt-get install -y apt-transport-https lsb-release ca-certificates
RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/php.list
RUN apt-get update
RUN apt-get install -y php7.3 php7.3-common php7.3-cli php7.3-fpm php7.3-json

# Phalcon
RUN curl -s https://packagecloud.io/install/repositories/phalcon/stable/script.deb.sh | sudo bash
RUN apt-get install -y php7.3-phalcon

# Composer
RUN wget -qO- https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    chmod +x /usr/local/bin/composer

# Yona CMS working dir
RUN mkdir -p /yona-cms
WORKDIR /yona-cms

# Finish
RUN mkdir -p /run/php
EXPOSE 9000
ENTRYPOINT ["/usr/sbin/php-fpm7.3", "-FO"]
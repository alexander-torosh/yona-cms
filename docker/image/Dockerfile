FROM debian:buster

RUN apt update
RUN apt install -y sudo wget curl git-core nano

# PHP 7.3
RUN apt install -y apt-transport-https lsb-release ca-certificates
RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/php.list
RUN apt update
RUN apt install -y php7.3 php7.3-common php7.3-cli php7.3-dev php7.3-fpm php7.3-mysql php7.3-xml php7.3-json php7.3-apcu php7.3-curl php7.3-mbstring

# Xdebug (optional)
RUN apt install -y php-xdebug

# Redis integration support
RUN apt install -y php7.3-redis redis-tools

# php-psr extension
WORKDIR /opt
RUN git clone https://github.com/jbboehr/php-psr.git
WORKDIR /opt/php-psr
RUN phpize && ./configure && make && make test && make install
RUN echo "extension=psr.so" >> /etc/php/7.3/mods-available/psr.ini
RUN phpenmod psr

# Phalcon v4
RUN curl -s https://packagecloud.io/install/repositories/phalcon/stable/script.deb.sh | sudo bash
RUN apt-get update
RUN apt-get install -y php7.3-phalcon

WORKDIR /

# Composer
RUN wget -qO- https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    chmod +x /usr/local/bin/composer

# MySQL client (optional)
RUN apt install -y default-mysql-client

# Yona CMS working dir
RUN mkdir -p /yona-cms
WORKDIR /yona-cms

# Finish
RUN mkdir -p /run/php
EXPOSE 9000
ENTRYPOINT ["/usr/sbin/php-fpm7.3", "-FO"]
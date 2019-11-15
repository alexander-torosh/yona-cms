FROM debian:buster

RUN apt update
RUN apt install -y sudo wget curl git-core nano

# PHP 7.3
RUN apt install -y apt-transport-https lsb-release ca-certificates
RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/php.list
RUN apt update
RUN apt install -y php7.3 php7.3-common php7.3-cli php7.3-dev php7.3-fpm php7.3-mysql php7.3-xml php7.3-json php7.3-psr php7.3-apcu

# Redis integration support
RUN apt install -y php7.3-redis redis-tools

# Zephir Parser
WORKDIR /opt
RUN apt install -y gcc make re2c autoconf automake
RUN git clone git://github.com/phalcon/php-zephir-parser.git
WORKDIR php-zephir-parser
RUN phpize && ./configure && make && make install
RUN echo "extension=zephir_parser.so" >> /etc/php/7.3/cli/php.ini

# Phalcon v4
WORKDIR /opt
RUN wget https://github.com/phalcon/cphalcon/archive/v4.0.0-rc.2.tar.gz && tar -zxvf v4.0.0-rc.2.tar.gz
WORKDIR cphalcon-4.0.0-rc.2
RUN wget https://github.com/phalcon/zephir/releases/download/0.12.11/zephir.phar && chmod +x zephir.phar
RUN php zephir.phar fullclean && php zephir.phar build
RUN echo "extension=phalcon.so" >> /etc/php/7.3/fpm/conf.d/50-phalcon.ini

WORKDIR /

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
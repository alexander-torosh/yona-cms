FROM debian:jessie

RUN export LC_ALL=C.UTF-8
RUN export TERM=xterm

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

RUN apt-get -y install apt-transport-https ca-certificates && \
wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg && \
echo "deb https://packages.sury.org/php/ jessie main" > /etc/apt/sources.list.d/php.list

RUN apt-get update && apt-get -y install php7.1-fpm php7.1-mysql php7.1-curl php7.1-redis php7.1-gd php7.1-intl

# Install composer global bin
RUN wget -qO- https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    chmod +x /usr/local/bin/composer

# Phalcon
RUN curl -s https://packagecloud.io/install/repositories/phalcon/stable/script.deb.sh | sudo bash
RUN sudo apt-get install -y php7.0-phalcon

RUN mkdir -p /yona-cms
WORKDIR /yona-cms

ENTRYPOINT ["/usr/sbin/php-fpm7.1", "-FO"]
FROM alpine:3.4
MAINTAINER Cal Evans <cal@calevans.com>

RUN apk add --no-cache bash vim wget sudo gcc autoconf automake make libtool \
        re2c flex bison git libc-dev openssl-dev libxml2-dev  zlib-dev \
        curl-dev libpng-dev icu-dev libmcrypt-dev libedit-dev g++ bzip2-dev \
        libxslt-dev; \
    cd /root; \
    git clone https://github.com/php/php-src.git; \
    wget -U "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:47.0) Gecko/20100101 Firefox/47.0" https://devzone.zend.com/downloads/buildphp.sh -O /root/buildphp.sh; \
    sh buildphp.sh; \
    cd /root; \
    wget https://getcomposer.org/installer; \
    php installer; \
    mv composer.phar /usr/bin/composer; \
    adduser -D cpkelly
VOLUME /opt
```
./configure --prefix=/usr/local/php/7.1 \
--with-config-file-path=/etc/php/7.1 \
--enable-fpm \
--with-fpm-user=www-data \
--with-fpm-group=www-data \
--enable-bcmath
--enable-mysqlnd \
--enable-opcache \
--enable-sockets \
--enable-sysvmsg \
--enable-sysvsem \
--enable-sysvshm \
--enable-shmop \
--enable-zip \
--enable-soap \
--enable-xml \
--enable-pcntl \
--enable-mbstring \
--enable-blackfire \
--with-mysql=mysqlnd \
--with-mysqli=mysqlnd \
--with-pcre-regex \
--with-iconv \
--with-zlib \
--with-mcrypt \
--with-gd \
--with-openssl \
--with-mhash \
--with-xmlrpc \
--with-curl \
--with-imap-ssl \
bcmath
blackfire
calendar
core
ctype
curl
date
dom
ereg
exif
fileinfo
filter
ftp
gd
gettext
hash
iconv
igbinary
imap
intl
json
libxml
mbstring
mcrypt
memcached
mhash
msgpack
mysql
mysqli
mysqlnd
openssl
pcntl
pcre
PDO
pdo_mysql
pdo_sqlite
pgsql
Phar
posix
readline
Refiection
session
shmop
SimpleXML
soap
sockets
SPL
sqlite3
standard
sysvmsg
sysvsem
sysvshm
tokenizer
wddx
xml
xmlreader
xmlwriter
xsl
Zend OPcache
zip
zlib
```

解决编译`PHP5.6` 时 `openssl` 库版本不兼容 `7.0 及以上 1.0 版本，5.6 不超过 1.0`

* 查看当前 `openssl` 版本

  `openssl verions -a`

* 编译安装 `openssl` 

  `wget https://www.openssl.org/source/old/0.9.x/openssl-0.9.8zh.tar.gz`

  `tar -xf openssl-0.9.8zh.tar.gz`

  `./config --prefix=/usr/local/openssl098 shared zlib`

  `ln -s /usr/local/openssl098/bin/openssl /usr/bin/openssl` // 要兼容新/旧版则不执行该指令

  `ln -s /usr/local/openssl098/include/openssl /usr/include/openssl` // 解决编译 `php-openssl` 时候找不到  `.h` 文件错误

  `echo "/usr/local/openssl098/lib" >> /etc/ld.so.conf`

  查看 `ldconfig -v`

  如果已安装有 `openssl` 则需要将 `cp /usr/local/openssl098/lib/libcrypto.so.0.9.8 /usr/lib`, 否则编译 `php` 回报错

* `php-openssl` 模块参数

  `--with-openssl=/usr/local/openssl098`

###解决编译 `php` 时候 `easy.h should be in <--dir>/includ/dir` 错误

在 `/usr/include` 创建符号连接

`cd /usr/include`

`sudo ln -s x86_64-linux-gnu/culr`


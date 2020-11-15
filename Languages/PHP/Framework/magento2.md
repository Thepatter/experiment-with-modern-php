### magento2

十分消耗资源，很重很慢，但在国外很流行，大公司维护，比较可靠与受客户（特别是国外）客户信赖。

#### 安装与配置

##### 技术栈及配置需求

2G 内存

需要以下技术栈：

* composer 暂时不支持 2.0

* nginx（1.x）/apache（2.4）

* mysql（8.0）/mariadb（10）

* elasticsearch（7.0）

* php7.2 及以上，需要以下扩展

    | extension | docker-php exists(PHP:7.4-FPM) |
    | :-------: | :----------------------------: |
    |  bcmath   |               no               |
    |   ctype   |              yes               |
    |   curl    |              yes               |
    |    dom    |              yes               |
    |    gd     |               no               |
    |   hash    |              yes               |
    |   iconv   |              yes               |
    |   intl    |               no               |
    | mbstring  |              yes               |
    |  openssl  |              yes               |
    | pdo_mysql |               no               |
    | simplexml |              yes               |
    |   soap    |               no               |
    |    xsl    |               no               |
    |    zip    |               no               |

##### docker 中运行

###### php-dockerfile

安装扩展

```dockerfile
FROM php:7.3.24-fpm-stretch

COPY sources.list /etc/apt
COPY magento.ini /usr/local/etc/php/conf.d

ENV username=c55018d4d8680c36bd35183e3be66aae password=3ce96aed3a088582bb81f73ab9f6bcf3

RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libicu-dev \
    libxml2-dev \
    libxslt1-dev \
    libzip-dev \
    libjpeg-dev \
    libwebp-dev \
    composer \
    git \
    && docker-php-ext-configure gd --with-jpeg-dir=/usr/include --with-webp-dir=/usr/include --with-png-dir=/usr/include --with-freetype-dir=/usr/include \
    && docker-php-ext-install  gd \
    && docker-php-ext-configure bcmath \
    && docker-php-ext-install  bcmath \
    && docker-php-ext-configure intl \
    && docker-php-ext-install  intl \
    && docker-php-ext-configure pdo_mysql \
    && docker-php-ext-install  pdo_mysql \
    && docker-php-ext-configure soap \
    && docker-php-ext-install soap \
    && docker-php-ext-configure zip \
    && docker-php-ext-install  zip \
    && docker-php-ext-configure xsl \
    && docker-php-ext-install  xsl \
    && docker-php-ext-configure sockets \
    && docker-php-ext-install  sockets \
    && pecl install redis-5.3.2 \
    && docker-php-ext-enable redis \
    && mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
```

###### docker-compose

magento 目录为代码目录，使用了外部定义的网络 local 方便配置其他容器加入，如从库、redis、mq

```yml
version: '3'

services:
  nginx:
    image: nginx:1.18
    container_name: docker-nginx
    hostname: docker-nginx
    environment:
      - TZ=Asia/Shanghai
    volumes:
      - ./conf:/etc/nginx/conf.d
      - ./magento:/var/www/html
    working_dir: /var/www/html
    user: :www-data
    ports:
      - 80:80
    networks:
      - local
    depends_on:
      - php
  php:
    build: ./php
    container_name: docker-php
    hostname: docker-php
    volumes:
      - ./magento:/var/www/html
    working_dir: /var/www/html
    user: :www-data
    environment:
      - TZ=Asia/Shanghai
    networks:
      - local
    depends_on:
      - mysql
      - elasticsearch
  mysql:
    image: mysql:8.0.22
    container_name: docker-mysql
    hostname: docker-mysql
    command: --default-authentication-plugin=mysql_native_password --character-set-server=utf8mb4 --collation-server=utf8mb4_unicode_ci
    environment:
      - TZ=Asia/Shanghai
      - MYSQL_ROOT_PASSWORD=secret
      - MYSQL_DATABASE=magento
      - MYSQL_USER=magento
      - MYSQL_PASSWORD=magento
    ports:
      - 13306:3306
    logging:
      driver: none
    networks:
      - local
  elasticsearch:
    image: elasticsearch:7.9.3
    container_name: elasticsearch
    hostname: elasticsearch793
    ports:
      - 9300:9300
      - 9200:9200
    environment:
      - TZ=Asia/Shanghai
      - discovery.type=single-node
    networks:
      - local
  rabbitmq:
    image: rabbitmq:3.8.9-management
    container_name: rabbitmq
    hostname: rabbitmq389m
    ports:
      - 5672:5672
      - 15672:15672
    environment:
      - RABBITMQ_ERLANG_COOKIE=secret
      - TZ=Asia/Shanghai
    networks:
      - local
  redis:
    image: redis:6
    container_name: redis
    hostname: redis6
    environment:
      - TZ=Asia/Shanghai
    ports:
      - 6379:6379
    networks:
      - local

networks:
  local:
    external: true
```

##### 安装及配置

###### 命令行安装

在 docker 中运行的前提是需要安装，在 php 容器中网站根目录执行

* 使用下载包安装

    1. 配置文件夹权限

        ```shell
        find var generated vendor pub/static pub/media app/etc -type f -exec chmod g+w {} +
        find var generated vendor pub/static pub/media app/etc -type d -exec chmod g+ws {} +
        chown -R :www-data . 
        ```

    2. 命令行安装

        在容器中安装时，base-url 不要配置成 127.0.0.1/localhost 使用域名，不然会循环跳转

        ```shell
        bin/magento setup:install \
        --base-url=http://localhost/magento2ee \
        --db-host=localhost \
        --db-name=magento \
        --db-user=magento \
        --db-password=magento \
        --backend-frontname=admin \
        --admin-firstname=admin \
        --admin-lastname=admin \
        --admin-email=admin@admin.com \
        --admin-user=admin \
        --admin-password=admin123 \
        --language=en_US \
        --currency=USD \
        --timezone=America/Chicago \
        --use-rewrites=1
        ```

* 使用 composer 安装

    会验证 access key，这里安装默认会创建子目录 magento2 需要在 nginx 中修改相应的 $MATE_ROOT

    ```shell
    composer create-project --repository=https://repo.magento.com/ magento/project-community-edition magento2
    ```

###### 配置

* 获取 composer access-key 用于验证下载 magento 扩展，主题

    在 marketplace.magento.com 注册并创建 access key，public key 为授权 username，private key 为授权 password，可以写入 php 镜像系统变量

    ```ini
    PublicKey: c55018d4d8680c36bd35183e3be66aae
    PrivateKey: 3ce96aed3a088582bb81f73ab9f6bcf3
    ```

* 常用命令，在网站根目录下使用 ./bin/magento 后接命令来运行

    |              命令              |        作用        |                             备注                             |
    | :----------------------------: | :----------------: | :----------------------------------------------------------: |
    |         setup:install          |    安装 magento    |                                                              |
    |        setup:uninstall         |    卸载 magento    |                           需已安装                           |
    |         setup:upgrade          |    更新 magento    |                         部署配置变更                         |
    |   mintenance:enable/disable    | 启用/关闭维护模式  |                           需已安装                           |
    |        setup:config:set        | 创建或更新部署配置 |                                                              |
    |     module:enable/disable      |   启用或警用模块   | 需要更新配置和删除缓存，被依赖模块不能禁用，启用时先启用依赖模块，模块冲突时不能同时启用 |
    |     setup:store-config:set     |    设置商店选项    |                           部署变更                           |
    |    setup:db-schema:upgrade     |   更新数据库设计   |                           部署更改                           |
    |     setup:db-data:upgrade      |   更新数据库数据   |                           部署更改                           |
    |        setup:db:status         |   检查数据库状态   |                           部署更改                           |
    |       admin:user:create        |     创建管理员     |                  需部署成功，启用管理员模块                  |
    |     cache:enable/disabale      |     cache 配置     |                                                              |
    |      info:language:list/       |      支持信息      |                                                              |
    |          indexer:info          |      索引操作      |                                                              |
    | sampledata:remove/deploy/reset |  样本数据模块操作  | 不会删除数据库样本数据，只是删除 composer.json 中模块，更新样本模块前需要 reset |

    默认使用 setup:upgrade 更新时会清理缓存的编译代码，只更新数据库设计和数据不清理编译代码使用 `--keep-generated` 选项（不要在开发环境中使用该选项，可能会报错）

###### 更改 magento

* 卸载模块支持代码、数据库、备份及相关控制

    ```shell
    bin/magento module:uninstall [--backup-code] [--backup-media] [--backup-db] [-r|--remove-data] [-c|--clear-static-content] \
      {ModuleName} ... {ModuleName}
    ```

    卸载时会


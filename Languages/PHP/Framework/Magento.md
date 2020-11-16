### Magento 相关笔记

#### 运行环境

##### docker

###### 技术栈及配置需求

2G 内存

需要以下技术栈：

* composer

  不支持 2.0

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

php.ini 配置

```ini
memory_limit = 2G
max_execution_time = 1800
zlib.output_compression = On
```

###### docker

* php-dockerfile

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

* docker-compose

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

##### Windows 平台

官方没有适配 window 版本，目前可以在 window 环境下安装，因为 URL 重写会使用软链接匹配规则的原因，无法在 window 下使用，官方文档也声明不支持 Windows 下运行但无法运行

###### 命令行安装

2.3 版本

安装过程中（Magento\Framework\Setup\Patch\PatchApplier.php）安装主题时会校验图片（/app/vendor/magento/theme-frontend-blank/media/preview.jpg），会去检查获取图片协议的 schema，其限定了

```php
$allowed_schemes = ['ftp', 'ftps', 'http', 'https'];
```

这四种范围，Windows 平台该图片名为绝对路径，因此需要修改其源码使其判断不存在才返回 false

```PHP
# \app\vendor\magento\framework\Image\Adapter\Gd2.php
private function validateURLScheme(string $filename) : bool
{
    $allowed_schemes = ['ftp', 'ftps', 'http', 'https'];
    $url = parse_url($filename);
    if ($url && isset($url['scheme']) && !in_array($url['scheme'], $allowed_schemes) && !file_exists($filename)) {
        return false;
    }

    return true;
}
```

修改 template 路径，替换成 windows 下分隔符

```php
# /app/vendor/magento/framework/View/Element/Template/File/Validator.php
protected function isPathInDirectories($path, $directories)
{
    if (!is_array($directories)) {
        $directories = (array)$directories;
    }
    // $realPath = $this->fileDriver->getRealPath($path);
    $realPath = str_replace('\\', '/', $this->fileDriver->getRealPath($path));
    foreach ($directories as $directory) {
        if (0 === strpos($realPath, $directory)) {
            return true;
        }
    }
    return false;
}
```

###### 运行

* 内置服务器

  但 worker 实在太慢了，根 docker 差不多，只是风扇不叫

  ```shell
  php -S localhost:9090 -t .\pub\ .\phpserver\router.php
  ```

* wamp

#### 配置

##### magento 获取

官网下载打包文件，需要登录

###### 使用 composer 获取

会验证 access key，这里安装默认会创建子目录 magento2 需要在 nginx 中修改相应的 $MATE_ROOT

```shell
composer create-project --repository=https://repo.magento.com/ magento/project-community-edition magento2
```

在用户目录创建 .composer 配置文件，配置 auth

```json
{
    "github-oauth": {
        "github.com": "2e92379cab0b1c6f812b18a40a3d5cfb45ad1b04"
    },
    "http-basic": {
        "repo.magento.com": {
            "username": "c55018d4d8680c36bd35183e3be66aae",
            "password": "3ce96aed3a088582bb81f73ab9f6bcf3"
        }
    }
}
```

##### 命令行安装

###### 安装 magento

在 docker 中运行的前提是需要安装，在 php 容器中网站根目录执行

1. 配置文件夹权限，官方文档推荐 g+w 实际上需要 a+w 权限，而且在命令行执行部分命令后会更新这些文件夹的权限，需要重新赋权才能修改配置

   ```shell
   find var generated vendor pub/static pub/media app/etc -type f -exec chmod a+w {} +
   find var generated vendor pub/static pub/media app/etc -type d -exec chmod a+w {} +
   chown -R :www-data . 
   ```

2. 命令行安装

   在容器中安装时，base-url 不要配置成 127.0.0.1/localhost 使用域名，不然会循环跳转

   ```shell
   bin/magento setup:install \
   --base-url=http://localhost \
   --backend_frontname=admin \
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
   --language=zh_CN \
   --currency= CNY \
   --timezone=Asia/Shanghai \
   --use-rewrites=1
   ```

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

###### 更改模块

* 启用或禁用模块

  某些模块在存在依赖关系时无法启用或禁用

  ```SHELL
  # moudle-list 使用空格分隔，-f 强制，-c 清除镜头文件
  bin/magento module:enable [-c|--clear-static-content] [-f|--force] [--all] <module-list>
  bin/magento module:disable [-c|--clear-static-content] [-f|--force] [--all] <module-list>
  ```

* 卸载（卸载时会将商店处于维护模式）模块支持代码、数据库、备份及相关控制

  ```shell
  # --backup-code 备份文件系统，不包含 var 和 pub/static，备份位置 var/backups/_filesystem.tgz
  # --backup-medis 备份 pub/media 目录,备份位置 /var/backups/_filesystem_media.tgz
  # --back-db 备份数据库, 位置 var/backups/_db.gz
  # --remove-data 删除数据库数据，要删除代码使用 composer remove
    bin/magento module:uninstall [--backup-code] [--backup-media] [--backup-db] [-r|--remove-data] [-c|--clear-static-content] \
      {ModuleName} ... {ModuleName}
  ```
  
* 使用备份回滚（回滚时商店会处于维护模式）

  ```shell
  bin/magento setup:rollback [-c|--code-file="<filename>"] [-m|--media-file="<filename>"] [-d|--db-file="<filename>"]
  ```

###### 更改配置

* 维护模式

  检测维护模式规则：如果 var/.maintenance.flag 不存在，则维护模式关闭，Magento 正常运行，使用 var/.maintenance.ip 排除 IP

  ```shell
  bin/magento maintenance:enable/disable [--ip=<ip address> ... --ip=<ip address>] | [ip=none]
  # 指定多个 ip 时，需要多次使用 --ip 选项
  bin/magento maintenance:enable --ip=192.0.2.10 --ip=192.0.2.11
  # 修改允许访问 ip
  bin/magento maintenance:allow-ips <ip address> .. <ip address> [--none]
  bin/magento maintenance:status
  ```

  magento 处于维护模式后，必须停止所有消息队列使用者进程（查找 `ps -ef | grep queue:consumers:start` 并 kill）

* 数据库操作

  更新模块/样本数据后需要更新数据库配置

  ```
  bin/magento setup:db-schema:upgrade
  bin/magento setup:db:status
  ```

###### 定时任务锁

默认使用数据库保存锁。多节点可以使用 zookeeper

###### 配置商店

* 修改商店相关选项

  ```shell
  bin/magento setup:store-config:set [--<parameter_name>=<value>, ...]
  ```

* 创建管理用户

  ```shell
  # 未指定参数会在交互式中询问
  bin/magento admin:user:create [--<parameter_name>=<value>, ...]
  bin/magento admin:user:create --admin-firstname=John --admin-lastname=Doe --admin-email=j.doe@example.com --admin-user=j.doe --admin-password=A0b9%t3g
  # 解锁管理员
  bin/magento admin:user:unlock {username}
  ```

#### 开发

magento 应用由模块（业务）、主题、语言包组成，构建模块时，必须遵循 PSR-4 兼容结构

##### 组件

###### 组件与包区别

组件即一个 psr4 依赖包，不过会兼容 magento 的规范：

* 包名为 module-{name}

* composer.json 中声明依赖关系

  ```json
  {
      "name": "magento/module-backend",
      "type": "magento2-module",
      "autoload": {
          "files": [
              "registration.php"
          ],
          "psr-4": {
              "Magento\\Backend\\": ""
          }
      }
  }
  ```

* 包根目录下创建一个 registration.php 文件在 magento 加载时注册

  ```PHP
  <?php
  
  use \Magento\Framework\Component\ComponentRegistrar;
  // 参数为 type、contentName、path
  ComponentRegistrar::register(ComponentRegistrar::MODULE, 'Magento_Backend', __DIR__);
  ```

* xml 声明文件，声明组件类型，Modules 对应 module.xml、Themes 对应 theme.xml、Language packages 对应 language.xml。一般主题和语言包直接在包根目录下创建对应的 xml 声明文件，模块会在根目录下创建一个 etc 文件夹中声明 xml 配置文件
* 可以在 Mangto Markerplace 上以 .zip 格式小于 30M 来分发

模块（扩展 Magento 功能），主题，语言包，打包时可以打包单个（`"type = magento2-module"`）需要多个组件协作时，需要打包成 metapackage（仅包含一个 composer.json 文件来指定组件依赖性）

```json
{
    "type": "metapackage"
}
```

###### 组件目录结构

组件结构和功能需保持单一，经量减少层次结构，推荐直接在 root 下创建目录，去掉组件下的 vendor 目录，单类型扩展（语言包、模块、主题），单组件 root 和仓库 root 目录结构相同，module 目录下 Test 保护单元测试。

每种组件类型都有不同的目录结构和不同内容的 composer.json（"type" 字段），包括 metapackage、magento2-module（更改 magento 行为）、magento2-theme、magento2-language、magento2-library（位于 lib/internal 非 vendor 目录的库）、magento2-component（完整的 magento 程序）

组件根目录与组件的名称匹配，并且包含其所有子目录和文件。根据安装 Magento 的方式，组件位于

* <install_path>/app（git 拉取时，所有组件位于此处），推荐开发位置，其结构为

  |   内容   |         位置         |
  | :------: | :------------------: |
  | modules  |       app/code       |
  | 商店主题 | app/design/frontend  |
  | 后台主题 | app/design/adminhtml |
  |  语言包  |       app/i18n       |
  | 配置文件 |       app/etc        |

* <install_path>/vendor

  使用 composer 或下载安装时位于此位置，magento 将第三方组件安装到 vendor 目录。推荐将组件添加到 <intall_path>/app/code 目录

* 模块典型目录结构

  |     目录     |      代码用途       |
  | :----------: | :-----------------: |
  |     Api      | 暴露给 API 的所有类 |
  |    Block     |  PHP view 的视图类  |
  |  Controller  |       控制器        |
  |   Console    |      cli 命令       |
  |     Cron     |      cron 作业      |
  | CustomerData |    包含分区数据     |
  |     etc      |      配置目录       |
  |    Helper    |    辅助函数文件     |
  |     i18n     |     国地化文件      |
  |    Model     |      逻辑实现       |
  |   Observer   |       监听器        |
  |              |                     |
  |              |                     |
  |              |                     |
  |              |                     |
  |              |                     |
  |              |                     |
  |              |                     |

  

###### 模块配置

每个模块都有一组配置文件，在 etc 目录。模块的配置 app/etc 顶层可以包含以下顶层配置文件（顶层所需的配置文件取决于新模块的功能和使用的方式。应尽量减小配置的作用域，少使用全局配置），其作用域为该组件全局：

|           文件           | 作用 |
| :----------------------: | :--: |
|         acl.xml          |      |
|        config.xml        |      |
|       crontabl.xml       |      |
|      db_schema.xml       |      |
| db_schema_whitelist.json |      |
|          di.xml          |      |
| extension_attributes.xml |      |
|        module.xml        |      |
|     {customize}.xml      |      |
|     {customize}.xsd      |      |
|        webapi.xml        |      |

和嵌套文件目录，其作用域为特定作用域，会覆盖对应作用域的全局配置。

|   子目录    |      作用域      |
| :---------: | :--------------: |
|  adminhtml  |       后台       |
|  frontend   |       前台       |
| webapi_rest |  rest api 接口   |
| webapi_soap | api 简单对象访问 |
|   graphql   |     graphql      |

##### 组件开发

开发前需要安装 magento 及其依赖并将其设置为开发者模式。包括布局文件结构，创建必要的配置文件，构建任何所需的 API 接口和服务以及添加组件所需的任何前端部件。构建过程中关闭缓存


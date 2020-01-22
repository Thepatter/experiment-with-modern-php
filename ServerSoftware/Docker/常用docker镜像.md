### 常用 docker 镜像

#### mirros

```json
{
  "registry-mirrors" : [
    "https://docker.mirrors.ustc.edu.cn/",
    "https://dockerhub.azk8s.cn",
    "https://reg-mirror.qiniu.com",
    "https://hub-mirror.c.163.com",
    "https://mirror.ccs.tencentyun.com"
  ]
}
```

#### MySQL

##### 启动服务

默认配置文件是 `/etc/mysql/my.cnf`

```shell
# 启动服务
docker run --name some-mysql -e MYSQL_ROOT_PASSWORD=my-secret-pw -e TZ="Asia/Shanghai" -d mysql:tag
# 启动另一个容器连接 some-mysql
docker run -it --network some-network --rm mysql mysql -hsome-mysql -uroot -p
# 作为客户端
docker run -it --rm mysql mysql -hsome.mysql.host -usome-mysql-user -p
```

##### 没有 cnf 文件时

许多配置选项可以作为标志传递给 `mysqld`。这将可以灵活的自定义容器而无需 cnf 文件。如，更改所有表的默认编码和排序规则使用UTF-8（utf8mb4)，只需执行如下命令：

```shell
# 传参配置
docker run --name <some-mysql> -p 3306:3306 -e MYSQL_ROOT_PASSWORD=<secret> -d mysql:<tag> --character-set-server=utf8mb4 --collation-server=utf8mb4_unicode_ci
# 可用选项的完整列表
docker run -it --rm mysql:tag --verbose --help
# 挂载 my.cnf 文件夹
docker run --name some-mysql -v /my/custom:/etc/mysql/conf.d -e MYSQL_ROOT_PASSWORD=my-secret-pw -d mysql:tag
```

##### 环境变量

启动 mysql 镜像时，可以通过在 `docker run` 命令行上传递一个或多个环境变量来调整 MySQL 实力的配置。如果使用已包含数据的数据目录启动容器，则以下任何变量都不会产生任何影响：任何预先存在的数据库在容器启动时始终保持不变

* `MYSQL_ROOT_PASSWORD`

  此变量时必须的，并指定将为 `root` 用户设置的密码

* `MYSQL_DATABASE`

  可选变量，指定在镜像启动时创建的数据库的名称。如果提供了用户/密码，则该用户将被授予对该数据库的超级用户访问权限（对应 GRANT ALL）

* `MYSQL_USER`，`MYSQL_PASSWORD`

  可选变量，结合使用来创建新用户并设置该用户的密码。

* `MYSQL_ALLOW_EMPTY_PASSWORD`

  可选变量，设置为 `yes` 允许以 `root` 用户的空密码启动容器

* `MYSQL_RANDOM_ROOT_PASSWORD`

  可选变量，设置为 `yes` 为 `root` 用户生成随机初始密码。生成的密码将打印到 `stdout`

* `MYSQL_ONETIME_PASSWORD`

  初始化完成后，将 `root` 设置为过期，在首次登录时强制更改密码。仅在 5.6+ 上支持此功能

##### 使用文件传递敏感信息

替代通过环境变量传递敏感信息的方法，`__FILE` 可以使初始化脚本从文件中加载变量

```shell
docker run --name <some-mysql> -p 3306:3306 -e MYSQL_ROOT_PASSWORD_FILE=/run/secrets/mysql-root -d mysql:tag
```

仅支持 `MYSQL_ROOT_PASSWORD`，`MYSQL_ROOT_HOST`，`MYSQL_DATABASE`，`MYSQL_USER`，`MYSQL_PASSWORD`

##### 连接到 mysql 服务容器

* 进入 mysql 服务容器的 bash 在 bash 中进入 mysql 命令后

  ```shell
  docker exec -it <some-mysql> bash
  ```

  启动另一个 `mysql` 容器来运行命令行客户端，连接 mysql 服务端容器

##### 存储数据的位置

* 让 `Docker` 通过使用自己的内部卷管理将数据库文件写入主机系统上的磁盘来管理数据库数据的存储。这是默认设置，对用户来说简单且透明，缺点是文件可能很难找到直接在主机系统上运行的工具和应用程序，即外部容器

* 在主机系统（容器外部）上创建一个数据目录，并将其挂载到容器内可见的目录中。这将数据库文件放置在主机系统上的已知位置，并使主机系统上的工具和应用程序可以轻松访问这些文件。缺点是用户需要确保目录存在，并且正确设置了主机系统上的目录权限和其他完全机制

  1.在主机系统上的适当卷上创建数据目录，如 `/my/own/datadir`

  2.启动容器并挂载目录

  ```shell
  docker run --name <some-mysql> -p 3306:3306 -v /my/own/datadir/:/var/lib/mysql -e MYSQL_ROOT_PASSWORD=secret -d mysql:tag
  ```

##### 转存数据库

* create databases dumps by docker-exec command

  ```shell
  docker exec some-mysql sh -c 'exec mysqldump --all-databases -uroot -p"$MYSQL_ROOT_PASSWORD"' > /some/path/on/your/host/all-databases.sql
  ```

* restoring data from dump files

  ```shell
  docker exec -i some-mysql sh -c 'exec mysql -uroot -p"$MYSQL_ROOT_PASSWORD"' < /some/path/on/your/host/all-databases.sql
  ```

##### docker stack

*stack.yml*

```yml
version: "3.1"
services:
	db:
		image: mysql:5.7.25
		command: --default-authentication-plugin=mysql_native_password
		restart: always
		environment:
			MYSQL_ROOT_PASSWORD: secret
	adminer:
		image: adminer
		restart: always
		ports:
			-8080:8080
```

运行 `docker stack deploy -c stack.yml mysql` 或 `docker-compose -f stack.yml up`，等待初始化完成后，访问 `http://swarm-ip:8080`，`http://localhost:8080`，`http://host-ip:8080`

#### Nginx

* *Dockerfile*

  ```dockerfile
  FROM nginx
  ADD app/ /app
  ADD nginx.conf /etc/nginx/nginx.conf
  ```

* run

  ```shell
  docker build --tag=nginx:0.0.1 .
  docker run --name nginx -itd -p 80:80 nginx:0.0.1
  ```

#### PHP-FPM

* *Dockerfile*

  ```do
  FROM php:7.4-fpm-alpine
  ADD app/ /app
  RUN apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS \
      && pecl install -o -f redis  \
      && docker-php-ext-enable redis \
      && rm -rf /usr/share/php \
      && rm -rf /tmp/* \
      && apk del  .phpize-deps
  ```

* run

  ```shell
  docker build --tag=php-fpm:0.0.1 .
  docekr run --name phpfpm -itd --network container:nginx php-fpm:0.0.1
  ```

#### Redis

```shell
# 启动并指定密码为 redispass
docker run --name redis -d -p 0.0.0.0:6379:6379 redis:5.0 --requirepass "redispass"
# 作为客户端使用连接容器
docker run -it --link redis-container:db --entrypoint redis-cli redis -h db
# 作为客户端连接
docker run -it redis redis-cli -h 182.92.223.239
# 使用自定义配置
docker run -v /myredis/conf/redis.conf:/usr/local/etc/redis/redis.conf --name myredis redis redis-server /usr/local/etc/redis/redis.conf
```

#### Hyperf 镜像

```shell
# 下载并运行 hyperf/hyperf 镜像，并将镜像内的项目目录绑定到宿主机的 /tmp/skeleton 目录
docker run --name hyperf -v /Users/z/PhpstormProjects/hyperf:/hyperf -p 9501:9501 -it --entrypoint /bin/sh hyperf/hyperf:7.2-alpine-cli
# 镜像容器运行后，在容器内安装 Composer
wget https://getcomposer.org/download/1.9.0/composer.phar
chmod u+x composer.phar
mv composer.phar /usr/local/bin/composer
# 将 Composer 镜像设置为阿里云镜像，加速国内下载速度
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer
# 通过 Composer 安装 hyperf/hyperf-skeleton 项目
composer create-project hyperf/hyperf-skeleton
# 进入安装好的 Hyperf 项目目录
cd hyperf-skeleton
# 启动 Hyperf
php bin/hyperf.php start
```

#### registry

```shell
docker run -d -p 5000:5000 --restart always --name registry registry:2.7.1
docker pull ubuntu:18.04
docker tag ubuntu:18 registry.com:5000/ubuntu:18
docker push registry:5000/ubuntu:18
```

需要在客户端 `daemon.json` 添加：

```json
{"insecure-registries" : ["home.com:5000"]}
```

并重启 docker 客户端




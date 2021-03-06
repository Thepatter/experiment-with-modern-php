### magento2

十分消耗资源，很重很慢，但在国外很流行，大公司维护，比较可靠与受（特别是国外）客户信赖。

#### 安装与配置

##### 获取 magento 软件

###### 配置需求

典型 LNMP/LAMP 架构

* composer 暂时不支持 2.0
* nginx（1.x）/apache（2.4）
* mysql（5.7/8.0）/mariadb（10）
* elasticsearch（6.8/7.0，2.4 版本必须配置）
* php7.2 及以上，需要（bcmath、ctype、curl、dom、gd、hash、iconv、intl、mbstring、openssl、pdo_mysql、simplexml、soap、xsl、zip）扩展

###### 获取 magento2

使用 git 和 composer 获取 magento 源码或插件时需要进行 magento markplace 身份验证。推荐在当前用户下创建 .composer 运行时目录放入 auth.json，在 marketplace.magento.com 注册并创建 access key，public key 为授权 username，private key 为授权 password，可以写入 php 镜像系统变量

```json
// ~/.composer/auth.json
{
    "github-oauth": {
        "github.com": "github-personal-token"
    },
    "http-basic": {
        "repo.magento.com": {
            "username": "marketplace-public-key",
            "password": "marketplace-private-key"
        }
    }
}
```

composer（需要身份认证）

会验证 access key，这里安装默认会创建子目录 magento2 需要在 nginx 中修改相应的 $MATE_ROOT

```shell
composer create-project --repository=https://repo.magento.com/ magento/project-community-edition magento2
```

使用 gitee 镜像仓库克隆或在官网下载打包文件

##### 安装

###### web 引导安装

配置好 lnmp 运行环境后访问首页会出现引导安装页面，2.4 已废弃，只能从命令行安装

###### 命令行安装

1. 配置文件夹权限

    ```shell
    find var generated vendor pub/static pub/media app/etc -type f -exec chmod g+w {} +
    find var generated vendor pub/static pub/media app/etc -type d -exec chmod g+ws {} +
    chown -R :www-data . 
    ```

2. 命令行安装（在容器环境下 base-url 不要配置成 127.0.0.1/localhost 要使用域名，不然 URI 重写时会循环跳转）

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
    --language=zh_Hans_CN \
    --currency=CNY \
    --timezone=Asia/Shanghai \
    --use-rewrites=1
    ```

##### 配置

###### 模式

```shell
# 切换
php ./bin/magento deploy:mode:set developer
php ./bin/magento deploy:mode:show
```

*   默认使用 default 模式，会检测代码更改，会缓存，会编译不存在代码

*   developer

*   production

    不会检查代码，只运行编译后的代码

###### 常用命令

* 常用命令，在网站根目录下使用 ./bin/magento 后接命令来运行

    |              命令              |        作用        |                             备注                             |
    | :----------------------------: | :----------------: | :----------------------------------------------------------: |
    |         setup:install          |    安装 magento    |                                                              |
    |        setup:uninstall         |    卸载 magento    |                           需已安装                           |
    |         setup:upgrade          |    更新 magento    |                    模块 schema、data 修改                    |
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
    | sampledata:remove/deploy/reset |  样本数据模块操作  | 不会删除数据库样本数据，只是删除 composer.json 中模块，更新样本模块前需要 reset，需要授权 |

    数据库设计变更、模块更新、样本代码部署时需要使用 setup:upgrade 更新配置。会清理缓存的编译代码，只更新数据库设计和数据，不清理编译代码使用 `--keep-generated` 选项（不要在开发环境中使用该选项，可能会报错）

###### 更改模块

* 启用或禁用模块存在依赖关系时无法启用或禁用，需先处理依赖关系

    ```SHELL
    # moudle-list 使用空格分隔，-f 强制，-c 清除静态文件
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

    检测维护模式规则：如果 var/.maintenance.flag 不存在，则维护模式关闭，Magento 正常运行，使用 var/.maintenance.ip 文件排除 IP

    ```shell
    # 支持多次使用 --ip 选项指定多个 IP
    bin/magento maintenance:enable/disable [--ip=<ip address> ... --ip=<ip address>] | [ip=none]
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

默认使用数据库保存锁来防止 corn 任务重复执行，多节点环境可以使用 zookeeper

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

##### 运行时配置

2.4 版本后台默认开启了两步验证，禁用 Magento_TwoFactorAuth 模块以取消

```shell
bin/magento module:disable Magento_TwoFactorAuth
```

###### 缓存

不启用缓存非常慢，启用缓存后每次修改文件需要刷新配置，可以禁用如下缓存来实现布局和显示的

```php
'cache_types' => 
      array (
        'config' => 1,
        'layout' => 0,
        'block_html' => 0,
        'collections' => 1,
        'reflection' => 1,
        'db_ddl' => 0,
        'eav' => 1,
        'customer_notification' => 1,
        'target_rule' => 1,
        'full_page' => 0,
        'config_integration' => 1,
        'config_integration_api' => 1,
        'translate' => 1,
        'config_webservice' => 1,
        'compiled_config' => 1,
      ),
```

默认启用文件缓存位于 <magento_root>/var/cache <magento_root>/var/page_cache

* 使用数据库缓存

  修改  <magento_root>/app/etc/di.xml。缓存数据将存储在 cache 和 cache_tag 表中。

  ```xml
  <!-- 节点所有前端缓存实例的内存相关配置 -->
  <type name="Magento\Framework\App\Cache\Frontend\Pool">
      <arguments>
        	<!-- 使 item 与 etc/env.php 中 cache 键中 frontend 数组对应 -->
          <argument name="frontendSettings" xsi:type="array">
            	<!-- name 为 env.php 中 cache 键数组 frontend 数组键值 -->
              <item name="page_cache" xsi:type="array">
                  <item name="backend" xsi:type="string">database</item>
              </item>
              <!-- env.php 中自定义 cache 的 id 可以指定多个 cache id -->
              <item name="<your cache id>" xsi:type="array">
              	<item name="backend" xsi:type="string">database</item>
              </item>
          </argument>
      </arguments>
  </type>
  <!-- 声明节点前端每个缓存类型配置 -->
  <type name="Magento\Framework\App\Cache\Type\FrontendPool">
      <arguments>
          <argument name="typeFrontendMap" xsi:type="array">
              <item name="backend" xsi:type="string">database</item>
          </argument>
      </arguments>
</type>
  ```

  修改 di.xml 和 env.php 文件后直接刷新即可看见结果，无需更新配置，验证时删除文件缓存并查看数据库
  
* 使用 redis 缓存

  ```bash
  # 指定页面和默认缓存使用 redis，会重写 env.php 中 cache 配置 frontend 对应配置
  php ./bin/magento setup:config:set --cache-backend=redis --cache-backend-redis-server=127.0.0.1 --page-cache-redis-db=0
  php ./bin/magento setup:config:set --page-cache=redis --page-cache-redis-server=127.0.0.1 --page-cache-redis-db=1
  # 存储会话
  php ./bin/magento setup:config:set --session-save=redis --session-save-redis-host=127.0.0.1 --session-save-redis-log-level=3 --session-save-redis-db=2
  ```
  
  或直接修改 env.php 文件
  
  ```php
  'session' => [
          'save' => 'redis',
          'redis' => [
              'host' => 'localhost',
              'port' => '6379',
              'database' => '0',
          ]
      ],
      'cache' => [
          'frontend' => [
              'default' => [
                  'backend' => 'Cm_Cache_Backend_Redis',
                  'backend_options' => [
                      'server' => 'localhost',
                      'port' => '6379',
                      'database' => '1',
                  ]
              ],
              'page_cache' => [
                  'backend' => 'Cm_Cache_Backend_Redis',
                  'backend_options' => [
                      'server' => 'localhost',
                      'port' => '6379',
                      'database' => '2',
                  ]
              ]
          ],
          'allow_parallel_generation' => false,
      ],
  ```

##### 部署

###### 生产模式

```bash
# 启用维护模式
magento maintenance:enable
# 编译
bin/magento setup:di:compile
# 部署静态文件
bin/magento setup:static-content:deploy
# 清缓存
bin/magento cache:flush
# 停用维护模式
magento maintenance:disable
```

##### 基础使用

#### 开发

magento 应用由模块（实现自定义业务逻辑，改变 magento 行为）、主题（前台和后台页面风格与设计）、语言包（本地化相关）组成，构建模块时，必须同时符合 magento 模块标准和 composer pacakge 标准

magento 开发/默认会自动编译代码，修改了 xml 配置后需要清理缓存，修改了 php 文件代码只需要清理缓存

##### magento 模块及命名空间

###### 前台

|   模块及命名空间    |      作用       |
| :-----------------: | :-------------: |
|       Catalog       | 分类和产品页面  |
|      Customer       |    用户中心     |
|      Checkout       |   购物车页面    |
|      Checkout       |      支付       |
|        Sales        |      订单       |
|       Search        |      搜索       |
|         Cms         | 首页及 Cms 页面 |
|       Contact       |    联系页面     |
| ConfigurableProduct |   可配置产品    |
|    Downloadable     |    下载产品     |

php 使用 plugin/preference/events 方式重写，phtml 直接在自定义模块下重写，xml 在自定义模块下使用 layout 重写

##### 组件

###### 组件与包区别

组件即一个 psr4 依赖包，不过会兼容 magento 的规范：

* composer.json 中声明依赖关系

    ```json
    {
        "name": "magento/module-backend", // 惯例以组件类型（module/theme/language）开头来命名
        // 打包为单个 magento2-module/language/theme 或 多个组件协作的 metapackage
        "type": "magento2-module",  
        "autoload": {
            "files": [
                "registration.php" // 组件注册文件，声明组件类型并注册到 magento
            ],
            "psr-4": {
                "Magento\\Backend\\": ""
            }
        },
        "config": {
            "sort-packages": true
        },
        "version": "102.0.1" // 2.0 组件版本为 102 开始
    }
    ```

* 包根目录下创建一个 registration.php 文件在 magento 加载时注册.

    ```PHP
    <?php
    use \Magento\Framework\Component\ComponentRegistrar;
    // 参数为 type（MODULE/THEME/LANGUAGE/LIBRARY）、contentName、path
    ComponentRegistrar::register(ComponentRegistrar::MODULE, 'Magento_Backend', __DIR__);
    ```
    
* xml 配置声明文件，Modules 对应 module.xml、Themes 对应 theme.xml、Language packages 对应 language.xml。一般主题和语言包直接在包根目录下创建对应的 xml 声明文件，模块会在根目录下创建一个 etc 文件夹保存模块用到的 xml 文件

* 可以在 Mangto Markerplace 上以 .zip 格式分发小于 30M 的组件

* 不需要分发组件，仅扩展 magento 功能时，只需要在 app 目录下按照组件目录结构进行开发测试与部署

###### 组件目录结构

组件结构和功能需保持单一，减少层次结构，推荐直接在组件根目录下创建目录不新增 vendor 目录，单类型扩展（语言包、模块、主题），单组件根目录和仓库根目录结构相同，module 目录下 Test 目录为测试目录。

每种组件类型都有不同的目录结构和不同内容的 composer.json（type 字段，包括 metapackage、magento2-module、magento2-theme、magento2-language、magento2-library（位于 lib/internal 非 vendor 目录的库）、magento2-component（完整的 magento 程序）

组件根目录与组件的名称匹配，并且包含其所有子目录和文件。根据安装 Magento 的方式，组件位于

* <install_path>/app（git 拉取时，所有组件位于此处），推荐新组件的开发位置，其结构为

    |         目录         |            代码             |
    | :------------------: | :-------------------------: |
    |       app/code       | 模块代码，改变 magento 行为 |
    | app/design/frontend  |          前台主题           |
    | app/design/adminhtml |          后台主题           |
    |       app/i18n       |         国际化文件          |
    |       app/etc        |          配置文件           |

* <install_path>/vendor

    使用 composer 或下载安装时位于此位置，magento 将第三方组件安装到 vendor 目录。推荐将组件添加到 <intall_path>/app/code 目录进行开发

* 模块典型目录结构（前缀为 app/code）

    |     目录     |                     代码用途                     |
    | :----------: | :----------------------------------------------: |
    |     Api      |               暴露给 API 的所有类                |
    |    Block     |                PHP view 的视图类                 |
    |  Controller  |                      控制器                      |
    |   Console    |                     cli 命令                     |
    |     Cron     |                    cron 作业                     |
    | CustomerData |                   包含分区数据                   |
    |     etc      |   配置目录，包含所有顶级和子目录 xml 配置文件    |
    |    Helper    |                   辅助函数文件                   |
    |     i18n     |           本地化文件，一般为 csv 文件            |
    |    Model     |                     逻辑实现                     |
    |   Observer   |                      监听器                      |
    |    Plugin    |                  插件，即拦截器                  |
    |    Setup     |      数据库结构/数据，在安装/升级时执行文件      |
    |      UI      |                  生成的数据文件                  |
    |     view     | 视图，包含静态视图，设计模版，邮件模版，布局文件 |
    |  ViewModel   |                   业务逻辑视图                   |

* 主题典型目录结构

    | 目录  |                      文件内容                       |
    | :---: | :-------------------------------------------------: |
    |  etc  |     配置文件（view.xml，图像和缩略图配置文件）      |
    | media |                       预览图                        |
    |  web  | css/ css/source/lib fonts images js 等 web 前端资源 |
    | i18n  |                   本地化文件 csv                    |

* 语言包典型目录结构只包含一个顶级目录，包含 language.xml、composer.json、registration.php 等文件，没有目录，文件夹后缀全小写默认与 ISO 语言名相同（magento/language-fr_fr）

###### 模块配置文件

每个模块都有一组配置文件，在 etc 目录。模块的配置 app/etc 顶层可以包含以下顶层配置文件（顶层所需的配置文件取决于新模块的功能和使用的方式。应尽量减小配置的作用域，少使用全局配置），其作用域为该组件全局：

|               文件               |           作用           |
| :------------------------------: | :----------------------: |
|         app/etc/acl.xml          |       资源权限配置       |
|        app/etc/config.xml        |        自定义配置        |
|       app/etc/crontab.xml        |       定时任务配置       |
|      app/etc/db_schema.xml       |    数据库建表定义相关    |
| app/etc/db_schema_whitelist.json | 建表字段，索引相关白名单 |
|          app/etc/di.xml          |     依赖注入相关配置     |
| app/etc/extension_attributes.xml |                          |
|        app/etc/module.xml        |         模块配置         |
|     app/etc/{customize}.xml      |                          |
|     app/etc/{customize}.xsd      |                          |
|        app/etc/webapi.xml        |       接口对应设置       |

子配置文件目录，其作用域为特定作用域，会覆盖对应作用域的全局配置。

|        子目录         |      作用域      |
| :-------------------: | :--------------: |
|  app/etc/adminhtml/*  |       后台       |
|  app/etc/frontend/*   |       前台       |
| app/etc/webapi_rest/* |  rest api 接口   |
| app/etc/webapi_soap/* | api 简单对象访问 |
|   app/etc/graphql/*   |     graphql      |

##### 组件开发

开发前需要安装 magento 及其依赖并将其设置为开发者模式。包括布局文件结构，创建必要的配置文件，构建任何所需的 API 接口和服务以及添加组件所需的任何前端部件。构建过程中关闭缓存。开发流程

1. 在 app/code 下创建模块，包含模块标准结构及文件，模块名与目录结构对应

2. 命令行启用模块，或在 app/etc/config.php 中启用模块

    ```
    php bin/magento module:enable Magento_module 
    ```

3. 更新结构

    ```shell
    php bin/magento setup:upgrade
    php bin/magento setup:static-content:deploy
    ```

###### 组件更新

组件更新时执行 `setup:db-data:upgrade` 时会扫描组件 Setup 文件夹下脚本，执行成功后会在 `patch_list` 表中增加一条记录，如果需要重复执行可以删除该表中的记录

###### 组件配置

在 /etc/module.xml 文件中声明自身

```xml
<?xml version="1.0"?>
<!-- 可以使用 urn 引用 xsd -->
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Module/etc/module.xsd">
    <!-- name 属性声明模块名,必须存在，如果不适用声明式安装与升级还必须声明 setup_version 属性 -->
    <module name="Vendor_ComponentName"/>
    <!-- 指定加载顺序，指定加载该组件前需要加载的组件列表 -->
    <sequence>
		<module name="Magento_Backend"/>
        <module name="Magento_Sales"/>
        <module name="Magento_Quote"/>
        <module name="Magento_Checkout"/>
        <module name="Magento_Cms"/>
    </sequence>
</config>
```

###### di.xm

作用注意是为 Service/Interface 选择实例或覆写 Service/Interface 的实例（preference 子元素），其 type 子元素主要是重写其实例的参数，并非为了构造实例，构造实例主要是根据类的构造方法声明进行实例化

```shell
# 获取对应类的注入项
bin/magento dev:di:info "Magento\Quote\Model\Quote\Item\ToOrderItem"
```

```xml
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:ObjectManager/etc/config.xsd">
    <!-- 继承 type 属性的虚拟类型，string 类型 system 值的构造函数 -->
    <virtualType name="moduleConfig" type="Magento\Core\Model\Config">
        <arguments>
            <argument name="type" xsi:type="string">system</argument>
        </arguments>
    </virtualType>
    <!-- App 所有实例接受 moduleConfig 作为依赖 -->
    <type name="Magento\Core\Model\App">
    <!-- 配置构造函数参数，参数名称须与配置类中构造函数中参数名称相对应 -->
        <arguments>
            <argument name="config" xsi:type="object">moduleConfig</argument>
        </arguments>
    </type>
</config>
```

* virtualType

  将不同的依赖项注入到现有 PHP 类中而不影响其他类且无需创建新类文件的方式。可以自定义类，而不会影响依赖于原始类的其他类

* 构造函数参数，可以在 argument 节点中配置类构造函数参数，支持以下类型

  ```xml
  <!-- string -->
  <argument xsi:type="string">{strValue}</argument>
  <argument xsi:type="string" translate="true">{strValue}</argument>
  <!-- boolean 支持 false|"false"* true|"true"* 和数字字符串 0/1 -->
  <argument xsi:type="boolean">{boolValue}</argument>
  <!-- number 支持整形和浮点型 -->
  <argument xsi:type="number">{numericValue}</argument>
  <!-- init_parameter 全局初始化常量 -->
  <argument xsi:type="init_parameter">{Constant::NAME}</argument>
  <!-- const 常量 -->
  <argument xsi:type="const">{Constant::NAME}</argument>
  <!-- null -->
  <argument xsi:type="null"/>
  <!-- array 支持嵌套 array -->
  <argument name="arrayParam" xsi:type="array">
      <!-- First element is value of constant -->
      <item name="firstElem" xsi:type="const">Magento\Some\Class::SOME_CONSTANT</item>
      <!-- Third element is a subarray -->
      <item name="thirdElem" xsi:type="array">
          <!-- Subarray contains scalar value -->
          <item name="scalarValue" xsi:type="string">
            ScalarValue
          </item>
      </item>
</argument>
  <!-- object 创建typeName类型实例作为参数传递，支持类、接口、虚拟类型-->
<argument xsi:type="object">{typeName}</argument>
  <!-- shared 定义创建对象方式 true（默认）单例第一次请求时创建，false 为每次创建-->
  <argument xsi:type="object" shared="{shared}">{typeName}</argument>
  <!-- 声明抽象或接口实现  -->
  <perference for="Magento\Core\Model\UrlInterface" type="Magento\Backend\Model\Url"/>
  ```
  
  Magento 合并给定范围的配置文件时，具有相同名称的数组参数将合并到新数组中，加载具体作用域配置时会替换其值。合并时，如果参数的类型不同，参数会用相同的名称替换其他参数，如果参数类型相同，则更新的参数将替换旧的参数
  
  多系统部署时，系统间共享 app/etc/config.php 中配置。不要在 app/etc/env.php 中存储敏感配置，也不要在生产环境和开发环境中共享该配置
  
  ```xml
  <type name="Magento\Config\Model\Config\TypePool">
      <arguments>
          <!-- 声明配置是敏感的 item name 属性指定配置项 item 值指定是(1)否(0)敏感 -->
          <argument name="sensitive" xsi:type="array">
              <item name="carriers/ups/username" xsi:type="string">1</item>
              <item name="carriers/ups/password" xsi:type="string">1</item>
          </argument>
          <!-- 声明配置是环境独有的 item name 属性指定配置项，值指定是(1)否(0)特定环境-->
          <argument name="environment" xsi:type="array">
              <item name="carriers/ups/access_license_number" xsi:type="string">1</item>
              <item name="carriers/ups/debug" xsi:type="string">1</item>
          </argument>
      </arguments>
  </type>
  ```

###### db_schema.xml

使用该文件来声明模块的 schema，可以用来修改表结构（定义表，修改表），Setup 文件夹下 UpgradeSchema 脚本也可以用来进行修改数据库表结构，但是是根据版本号来进行升级的，会强制要求升级模块，但 Setup 目录 patch 数据不会升级模块

如果在同一次更改中修改了表结构和修改了数据，使用 setup:upgrade 会报错（因为其先执行 data:upgrade 再执行 schema:upgrade），可以分开执行

1. 先执行 php ./bin/magento setup:db-schema:upgrade
2. 再执行 php ./bin/magento setup:db-data:upgrade

*   table 节点

    可以包含一个或多个 table 节点，每个节点代码数据库中一个表，可以包含一下属性

    |   属性   |                  描述                   |
    | :------: | :-------------------------------------: |
    |   name   |                  表名                   |
    |  engine  |          只支持 innodb/memroy           |
    | resource | 数据库分片，支持 default/checkout/sales |
    | comment  |                  注释                   |

    可以包含三种类型的子节点：

    column

    |   属性    |                             描述                             |
    | :-------: | :----------------------------------------------------------: |
    | xsi:type  | 列类型：blob/boolean/date/datetime/decimal/float/int/read/smallint/text/timestamp/varbinary/varchar |
    |   name    |                            列名称                            |
    |  default  |                           初始化值                           |
    | disabled  |             禁用或删除已声明的表、列、约束、索引             |
    | identity  |                          列是否自增                          |
    |  length   |                            列长度                            |
    | nullable  |                         是否可以为空                         |
    | onCreate  | DDL 触发器，将数据从现有列移动到新创建的列，仅创建列时起作用 |
    |  padding  |                          整数列大小                          |
    | precision |                   实际数据类型中允许的位数                   |
    |   scale   |                 实际数据类型中小数点后的位数                 |
    | unsigned  |                           数据属性                           |

    ```xml
    <column xsi:type="int" name="entity_id" padding="10" unsigned="true" nullable="false" identity="true" comment="Credit ID"/>
    ```

    constraint 子节点

    |    属性     |                    描述                     |
    | :---------: | :-----------------------------------------: |
    |    type     |           primary/unique/foreign            |
    | referenceId | 仅限于 db_schema.xml 文件范围内的关系映射。 |

    ```xml
    <constraint xsi:type="primary" referenceId="PRIMARY">
        <column name="entity_id"/>
    </constraint>
    <!-- 外键约束 table 当前表名称、column 当前表外键列、referenceTable 被引用表，referenceColumn 引用表列 onDelete 触发器 -->
    <constraint xsi:type="foreign" referenceId="COMPANY_CREDIT_COMPANY_ID_DIRECTORY_COUNTRY_COUNTRY_ID"
                table="company_credit" 
                column="company_id" 
                referenceTable="company" 
                referenceColumn="entity_id" 
                onDelete="CASCADE"/>
    ```

    index 子节点

    |    属性     |                             描述                             |
    | :---------: | :----------------------------------------------------------: |
    |    type     |                     btree/fulltext/hash                      |
    | referenceId | 仅限于 db_schema.xml 文件范围内的关系映射，自定义一个唯一的引用，在 whitelist.json 中引用 |

    ```xml
    <index referenceId="NEWSLETTER_SUBSCRIBER_CUSTOMER_ID" indexType="btree">
        <column name="customer_id"/>
    </index>
    ```

创建新表后要生成 db_schema_whitelist.json，无法在使用前缀的实例上生成百名的

```shell
# [options] 可以声明 --module-name[=MODULE-NAME] 指定要为其生成白名单的模块，默认为所有模块生成白名单
bin/magento setup:db-declaration:generate-whitelist [options]
```

重命名表格，将删除旧表并创建新表。不支持同时从另一个表迁移数据和重命名列，重命名表时，要重新生成 db_schema_whitelist.json 文件

```xml
<schema xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"              xsi:noNamespaceSchemaLocation="urn:magento:framework:Setup/Declaration/Schema/etc/schema.xsd">
    <!-- 修改后的 -->
    <table name="new_declarative_table" onCreate="migrateDataFromAnotherTable(declarative_table)">
    <!-- 原来的 -->
    <table name="declarative_table">
        <column xsi:type="int" name="id_column" padding="10" unsigned="true" nullable="false" comment="Entity Id"/>
        <column xsi:type="int" name="severity" padding="10" unsigned="true" nullable="false" comment="Severity code"/>
        <column xsi:type="varchar" name="title" nullable="false" length="255" comment="Title"/>
        <column xsi:type="timestamp" name="time_occurred" padding="10" comment="Time of event"/>
        <constraint xsi:type="primary" referenceId="PRIMARY">
            <column name="id_column"/>
        </constraint>
    </table>
</schema>

```

表格添加列时，要生成 db_schema_whitelist.json 文件，删除列时仅当 db_schema_whitelist.json 中存在该列时才能删除。支持更改列类型。

要重命名一列，需要删除原始列兵创建一个新的列。在新的列声明中，使用 onCreate 属性指定迁移的数据的列，重命名列时要重新生成 db_schema_whitelist.json 文件，以便包含旧名称的同时还包含新名称

```xml
onCreate="migrateDataFrom(entity_id)"
```

添加索引，在表定义中直接添加，删除索引，在表定义的节点中删除定义索引的节点后再将 whitelist.json 中对应表的 index 下该 index 的 referenceId 设置 false

```xml
# 添加索引
<index referenceId="INDEX_SEVERITY" indexType="btree">
    <column name="severity"/>
</index>
# 添加外键，只有在 db_schema.xml 包含两个表时才能创建外键
<constraint xsi:type="foreign" referenceId="FL_ALLOWED_SEVERITIES" table="declarative_table"
            column="severity" 
            referenceTable="severities" referenceColumn="severity_identifier"
            onDelete="CASCADE"/>
```

###### db_schema_whitelist.json

配置 db_schema.xml 来进行数据库表结构的更新，其定义类似，对应允许存在的相关定义为 true，需要删除的在 db_schema.xml 中删除定义后再对应的设置中设置为 false：

```json
{
    "table": {
        "column": {
            "column_name_add": true,
            "column_name_delete": false,
        },
        "constraint": {
            "CONSTRAINT_DEFINE_REFERENCE_ID": true
        },
        "index": {
        	"INDEX_DEFINE_REFERENCE_ID": true
   		}
    }
}
```

###### config.xml

配置对应模块的相应配置的值，如 system.xml 中对应 field 的默认值

*etc/adminhtml/system.xml*

```xml
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:module:Magento_Config:etc/system_file.xsd">
    <system>
        <section id="sales">
            <group id="general">
                <field id="test" translate="label" type="textarea" sortOrder="710" showInDefault="1" showInWebsite="1" showInStore="1" canRestore="1">
                    <label>test</label>
                    <comment>{var} is the template variable, don't modify them.</comment>
                </field>
            </group>
        </section>
    </system>
</config>
```

*etc/config.xml*

配置后台相关元素元素默认值（section/group/field）

```xml
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:module:Magento_Store:etc/config.xsd">
    <default>
        <sales>
            <general>
                <test><![CDATA[default test data]]></test>
            </general>
        </sales>
    </default>
</config>
```

* 程序中获取配置项

  ```php
  private $scopeConfig;
  public function __construct(ScopeConfigInterface $scopeConfig)
  {
      $this->scopeConfig;
      $storeValue = $this->scopeConfig->getValue('section/group/field', Store::Default);
  }
  ```

###### events.xml

定义对应组件 model 的 observer，会在 model 状态符合 event 时进行相关操作，magento 自身的 model（如 `Magento\Sales\Model\Order` 等）源码上会记录其支持的相关 event

```xml
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Event/etc/events.xsd">
    <event name="sales_order_save_after">
        <observer name="order_complete_send_sms" instance="Silksoftwarecorp\Sales\Observer\Order\OrderCompleteSendSms"/>
    </event>
</config>
```

配置默认值

##### 功能项

###### 后台缓存管理项新增

1.  在 etc/cache.xml 文件中配置一个可在管理后台操作的缓存项

    ```xml
    <?xml version="1.0"?>
    <config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Cache/etc/cache.xsd">
      	<!-- name 唯一缓存类型 id, translate 后台 Cache Management 展示项 -->
        <type name="%cache_type_id_unique%" 
              translate="label,description" 
              instance="VendorName\ModuleName\Model\Cache\Type\CacheType">
          	<!-- 后台缓存控制 Cache Type 字段展示 -->
            <label>Cache Type Label</label>
          	<!-- 后台缓存控制 Description 字段展示 -->
            <description>Cache Type Description</description>
        </type>
    </config>
    ```

2.  创建 cache.xml 中 instance

    ```php
    <?php
    
    namespace Temp\CacheChange\Model\Cache\Type;
    
    use Magento\Framework\App\Cache\Type\FrontendPool;
    use Magento\Framework\Cache\Frontend\Decorator\TagScope;
    
    class DBCache extends TagScope
    {
        const TYPE_IDENTIFIER = 'db_cache_id';
    
        const CACHE_TAG = 'db_cache_tag';
    
        public function __construct(FrontendPool $cacheFrontendPool)
        {
            parent::__construct($cacheFrontendPool->get(self::TYPE_IDENTIFIER), self::CACHE_TAG);
        }
    
        public function clean($mode = \Zend_Cache::CLEANING_MODE_ALL, array $tags = [])
        {
            return parent::clean($mode, $tags); // TODO: Change the autogenerated stub
        }
    }
    ```

3.  在 etc/env.php 中启用

    ```php
    'cache_type' => [
    	'db_cache_id' => 1,
    ]
    ```
    
4.  安装模块

    ```shell
    php ./bin/magento setup:upgrade
    ```

###### full-page-cache-control

默认所有页面都可以缓存，如果页面布局文件中包含不缓存的 block，则整个页面都是不缓存的，配置时在对应的 layout 中配置其缓存属性，或者在响应头中控制缓存属性

```xml
<block class="Magento\Paypal\Block\Payflow\Link\Iframe" 		template="payflowlink/redirect.phtml" cacheable="false"/>
```

定义缓存时，可以在管理员界面或编写代码控制

###### CLI命令

命令在模块范围内定义。创建命令的流程：

1. 在 Console 中创建命令类，继承 Symfony\Component\Console\Command\Command，在 execute 方法中处理命令逻辑，在 configure/__construct 中定义命令相关配置或在 di.xml 中定义相关配置

2. 在组件 etc/di.xml 中注入命令

   ```xml
   <config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:ObjectManager/etc/config.xsd">
       <type name="Magento\Framework\Console\CommandListInterface">
           <arguments>
               <argument name="commands" xsi:type="array">
                   <item name="commandexample_somecommand" xsi:type="object">Magento\CommandExample\Console\Command\SomeCommand</item>
               </argument>
           </arguments>
       </type>
   </config>
   ```

3. 清除缓存后注入并编译

   ```shell
   bin/magento cache:clean
   bin/mangeot setup:di:compile
   ```

###### API

在 <Module>/etc/webapi.xml 文件中定义

|     元素      |                             属性                             |                描述                |
| :-----------: | :----------------------------------------------------------: | :--------------------------------: |
|  `<routes>`   | `xmlns:xsi`（必须）`xmlns:noNamespaceSchemaLocation`（必须） |               根元素               |
|   `<route>`   | `method` 必须，请求方法：GET、POST、PUT、DELETE；`url`，必须以 v(int) 开始，必须在模版参数前加上冒号 `/V1/products/:sku`；`secure` 布尔，是否仅 https；`soapOperation` 声明 soap 操作 |          定义 HTTP Route           |
|  `<service>`  | `class`，必须，定义实现接口类；`method` 必须，定义支持请求方法 | route 子元素，定义实现接口和方法名 |
| `<resources>` |           route 子元素，定义一个或多个资源访问范围           |                                    |
| `<resource>`  | `ref`：声明资源访问权限，支持 self、anonymous、magento resource |          resources 子元素          |
|   `<data>`    |                                                              |       route 子元素，定义参数       |
| `<parameter>` |               `name` 属性名；`force` 是否强制                |            data 子元素             |

开发接口流程：

1. 定义 module_name/etc/webapi.xml 配置文件

   ```XML
   <?xml version="1.0"?>
   <routes xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:noNamespaceSchemaLocation="urn:magento:module:Magento_Webapi:etc/webapi.xsd">
       <!-- Customer Group Service-->
       <route url="/V1/resource/:page" method="GET">
           <service class="module\namespace\Api\ProcessInterface" method="index"/>
           <resources>
               <resource ref="anonymous"/>
           </resources>
       </route>
   </routes>
   ```

2. 在 module_name/Api 目录下定义服务接口类，一个类中的方法可以处理一个 webapi.xml 中的 route，这里定义的接口不需要继承或扩展其他接口，但方法必须定义 phpdoc 注释，声明参数和返回值类型，支持标量、数组（不支持关联数组）、对象，对于要返回一个普通的 key=>value 对的 json 字符串，需要预先定义一个类

   ```PHP
   // 接口方法中的参数名和 webapi.xml 中的参数名一致, 此时 page 值为 url 路径中的 page 值
   public function index(int $page);
   ```

3. 在 module_name/etc/di.xml 中定义实现 api 的类

4. 访问时路径前加 rest 前缀

webapi.xml

```xml
<?xml version="1.0"?>
<routes xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:module:Magento_Webapi:etc/webapi.xsd">
  <!-- Customer Group Service-->
  <route url="/V1/customerGroups/:id" method="GET">
    <service class="Magento\Customer\Api\GroupRepositoryInterface" method="getById"/>
    <resources>
      <resource ref="Magento_Customer::group"/>
    </resources>
  </route>
  <route url="/V1/customers/me/billingAddress" method="GET">
    <service class="Magento\Customer\Api\AccountManagementInterface" method="getDefaultBillingAddress"/>
    <resources>
      <resource ref="self"/>
    </resources>
    <data>
      <parameter name="customerId" force="true">%customer_id%</parameter>
    </data>
  </route>
</routes>
```

###### 后台功能开发

* 如果使用 Grid 类进行渲染布局，Block 文件夹下 Edit 必须创建文件 Edit/From.php 在其中修改 save 的 url 才可以，直接在 Grid 的 Edit.php 中修改 getSaveUrl 无法成功

1. 配置菜单 <module>/etc/mean.xml

   ```xml
   <?xml version="1.0"?>
   <config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:module:Magento_Backend:etc/menu.xsd">
       <menu>
           <add id="define_unique_id::acl_xml_use_the_id"
                title="show in backend"
                module="module_name"
                sortOrder="35
                <!-- 入口路由 -->
                action="default/index/index"
                <!-- acl中引用的 resource -->                 
                resource="acl_xml::resource"
                <!-- 管理后台上级页面 -->
                parent="Magento_Backend::marketing_user_content"/>
           <!-- 可以使用 remove id 移除自带的 -->
           <remove id="Magento:Origin_Source"/>
       </menu>
   </config>
   ```

2.  配置后台 acl <module>/etc/acl.xml

    ```xml
    <?xml version="1.0"?>
    <config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:noNamespaceSchemaLocation="urn:magento:framework:Acl/etc/acl.xsd">
        <acl>
          <!-- 后台根层级开始查找 -->
            <resources>
                <resource id="Magento_Backend::admin">
                    <resource id="Magento_Backend::marketing">
                        <resource id="Magento_Backend::marketing_user_content">
                            <!-- mean.xml 中定义的 resource -->
                            <resource id="meau_xml_define_resource"
                                      title="show in backend"
                                      sortOrder="35"/>
                        </resource>
                    </resource>
                </resource>
           </resources>
        </acl>
    </config>
    ```

3.  模块正常的开发逻辑

###### 后台新增配置含文件上传

section 元素中如果未定义 resource 可能导致不显示

1. 在 <Module>/etc/adminhtml/system.xml 中增加 section

   ```xml
   <?xml version="1.0"?>
   <config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:module:Magento_Config:etc/system_file.xsd">
       <system>
           <!-- 管理后台 section -->
           <section id="general">
               <!-- 管理后台 section 下分组配置 -->
               <group id="group_config" translate="label" type="text" sortOrder="200" showInDefault="1" showInWebsite="0" showInStore="1">
                   <!-- 管理后台 section-group 显示的 label -->
                   <label>Backend Group Config</label>
                   <!-- 后台配置字段，会存储在 core-config-data 表中 -->
                   <field id="enabled" 
                          translate="label comment" <!-- 定义后台显示的项 -->
                          type="select" <!-- 下拉选项,支持文件，文本等 -->
                          sortOrder="10" <!-- 在 group 的排序，小的排在前面 -->
                          showInDefault="1" <!-- 在默认配置(Default Config)范围是否显示 -->
                          showInWebsite="0" <!-- 在主站(Main Website)范围是否显示 -->
                          showInStore="0">  <!-- 在店铺范围是否显示 -->
                       <!-- 字段值显示的 lable 显示在上面 -->
                       <label>Enable Current Config</label>
                   	<!-- 指定下拉资源 -->
                       <source_model>Magento\Config\Model\Config\Source\Yesno</source_model>
                   	<!-- 字段的 comment 会显示在字段的下面较小字体 -->
                       <comment>this is comment</comment>
                   </field>
                   <field id="text_conf" translate="label" type="text" sortOrder="20" showInDefault="1" showInWebsite="0" showInStore="0">
                       <label>Text Config Label</label>
                   </field>
                   <field id="image_conf" translate="label" type="image" sortOrder="30" showInDefault="1" showInWebsite="1" showInStore="1" canRestore="1">
                       <label>FILE LABEL</label>
                       <!-- 定义后台处理 model，用于文件操作相关 -->
                       <backend_model>Your\Module\Model\Config\Backend\Image</backend_model>
                       <!-- 定义范围 -->
                       <base_url type="media" scope_info="1">live_popup</base_url>
                   </field>
               </group>
           </section>
       </system>
   </config>
   ```
   
2. 定义 backend_model 可以指定 front_model 等其他，处理文件上传直接继承 \Magento\Config\Model\Config\Backend\Image，对其做了较好封装

3. 模板文件使用配置

   在 `design\frontend\*\templates` 目录下定义 *.phtml 文件，可以 `design\frontend\*\layout\default.xml` 中配置该模板的 Block

   ```xml
   <?xml version="1.0"?>
   <!--
   /**
    * Copyright © Magento, Inc. All rights reserved.
    * See COPYING.txt for license details.
    */
   -->
   <page layout="3columns" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">
       <update handle="default_head_blocks"/>
       <body>
           <referenceContainer name="before.body.end">
               <block class="Magento\Framework\View\Element\Template" name="{unique_name}"
                      <!-- 定义 tmeplates 下模板文件，声明其是否启用配置 -->
                      template="Magento_Theme::{tmplates_name}.phtml" ifconfig="core/config/data/table/path"/>
           </referenceContainer>
       </body>
   </page>
   ```

4. 模板文件中访问配置

   ```php
   // 预先定义 config 获取配置
   $_helper = $this-helper(Your\Module\Helper\Config::class);
   // 获取配置
   $textConfig = $_helper->getConfig('core-config-data-path');
   // 使用 echo 输出，获取 media 下路径
   <img src="<?php echo $block->getUrl('pub/media/{upload_dir}/').$_helper->getConfig('core_config_path');?>
   ```

###### 数据库操作

* 打印 SQL 语句

  ```php
  // 三者等价
  $collection->getSelect()->assemble();
  $collection->getSelect()->__toString();
  echo $collection->getSelect(); 
  ```

* 指定字段查询

  如果不调用 reset 会查询 * 和 columns 中的字段，调用 reset 后只查询 columns 中的字段

  ```php
  $collection->getSelect()
              ->reset(\Zend_Db_Select::COLUMNS)
              ->columns(['id']);
  ```

* 使用 connection

  ```php
  // 删除
  $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
  $connection = $resource->getConnection();
  $myTable = $resource->getTableName('mytable');
  $connection->delete(
      $myTable,
      ['order_id = ?' => 10000]
  );
  // 普通查询
  $columns = ['col_key', 'col_value'];
  $select = $this-connection->select()->from($myTable, $columns)->where('id > 1', '100');
  // 返回 ['col_key' => 'col_value'] 的 map 结构，如果 columns 数量超过 2 个，也只获取前两个字段的数组
  $fetchPair = $this->connection->fetchPairs($select);
  // 返回所有数据对象 columns 的 col_key => [column => value] 的数组
  $fetchAssoc = $this->connection->fetchAssoc($select);
  // 返回所有数据对象 columns 的 column => value
  $fetchAll = $this->connection->fetchAll($select);
  // 返回第一个数据对象 column => value 数组
  $fetchRow = $this->connection->fetchRow($select);
  // 返回第一个数据对象的第一个字段，返回值是一个标量
  $fetchOne = $this->connection->fetchOne($select);
  // 返回所有数据对象中 columns 中第一个字段的索引数组
  $fetchCol = $this->connection->fetchCol($select);
  
  ```

* resourceCollection 相关操作，注意 ui_comment 中 columns 字段名和 collection 中的查询要字段名对应，不然会存在找不到对应字段名情况

  * 属性筛选

    ```php
    // Equals: eq
    $_products->addAttributeToFilter('status', array('eq' => 1)); // Using the operator
    $_products->addAttributeToFilter('status', 1); // Without using the operator
    // Not Equals - neq
    $_products->addAttributeToFilter('sku', array('neq' => 'test-product'));
    // Like - like
    $_products->addAttributeToFilter('sku', array('like' => 'UX%'));
    // Not Like - nlike
    $_products->addAttributeToFilter('sku', array('nlike' => 'err-prod%'));
    // In - in
    $_products->addAttributeToFilter('id', array('in' => array(1,4,98)));
    // Not In - nin
    $_products->addAttributeToFilter('id', array('nin' => array(1,4,98)));
    // NULL - null
    $_products->addAttributeToFilter('description', array('null' => true));
    // Not NULL - notnull
    $_products->addAttributeToFilter('description', array('notnull' => true));
    // Greater Than - gt
    $_products->addAttributeToFilter('id', array('gt' => 5));
    // Less Than - lt
    $_products->addAttributeToFilter('id', array('lt' => 5));
    // Greater Than or Equals To- gteq
    $_products->addAttributeToFilter('id', array('gteq' => 5));
    // Less Than or Equals To - lteq
    $_products->addAttributeToFilter('id', array('lteq' => 5));
    ```

  * 属性排序

    ```php
    $_products->addAttributeToSort($attribute, $dir = self::SORT_ORDER_ASC);
    // 或者
    $this->getSelect()->order($this->_getAttributeFieldName($attribute) . ' ' . $dir);
    ```


###### 前台功能项开发

##### eav 相关

entity-attribute-value 模型

* 所有 attribute 都会定义在 eav_attribute 表中
* 对应的 attribute type 定义在 eav_attribute_type 表中（`eav_attribute.entity_type_id = eav_entity_type.entity_type_id`）

###### 发送邮件

1. 在 etc 目录下定义 email_templates.xml 配置

   ```xml
   <?xml version="1.0"?>
   <config xmlns:xsi="">
   	<template id="模板唯一id" lable="模板label" file="模板文件位置" type="模板文件类型" module="模块" area="区域">
   </config>
   ```

   id 会在 transportBuilder 类创建 transport 时使用；file 默认为 `<module>/view/{frontend/adminhtml}/email/` 目录下定义的文件；type：file 的类型，文本或 html，area 区域，对应 view 下区域

2. 定义对应 html

   ```html
   <!--@subject 自定义邮件主题 @-->
   <h3>hi {{var name}}</h3>
   ```

   在顶部注释中定义主题，定义变量使用双括号，两边没有空格，且使用 var 开头

3. 设置并发送，使用 transport 发送

   ```php
   public function __construct(\Magento\Framework\Mail\Template\TransportBuilder $transportBuilder);
   $transport = $this->transportBuilder
       ->setFromByScope('')
       ->addTo(['test@admin.com', 'test1@admin.com'])
       ->setTemplateIdentifier('email_templates.xml 中定义的唯一的 template id')
       ->setTemplateVars([])
       ->setTempateOption([])
       ->getTransport();
   $transport->sendMessage();
   ```

* 添加附件

  并未内置开箱即用的发送附件方式，2.3 开始发送附件方式

  1. 重写 TransportBuilder 类

     ```php
     <?php
     
     namespace YourModule\Model\Email;
     
     use Magento\Framework\Mail\MessageInterface;
     use Magento\Framework\Mail\MessageInterfaceFactory;
     use Magento\Framework\Mail\Template\FactoryInterface;
     use Magento\Framework\Mail\Template\SenderResolverInterface;
     use Magento\Framework\Mail\TransportInterfaceFactory;
     use Magento\Framework\ObjectManagerInterface;
     use Laminas\Mime\Mime;
     use Laminas\Mime\Part as MimePart;
     use Laminas\Mime\PartFactory as MimePartFactory;
     use Laminas\Mime\Message as MimeMessage;
     use Laminas\Mime\MessageFactory as MimeMessageFactory;
     
     
     class OwnTransportBuilder extends \Magento\Framework\Mail\Template\TransportBuilder
     {
         /** @var MimePart[] */
         private $parts = [];
     
         /** @var MimeMessageFactory */
         private $mimeMessageFactory;
     
         /** @var MimePartFactory */
         private $mimePartFactory;
     
         public function __construct(
             FactoryInterface $templateFactory,
             MessageInterface $message,
             SenderResolverInterface $senderResolver,
             ObjectManagerInterface $objectManager,
             TransportInterfaceFactory $mailTransportFactory,
             MimePartFactory $mimePartFactory,
             MimeMessageFactory $mimeMessageFactory,
             MessageInterfaceFactory $messageFactory = null
         ) {
             parent::__construct(
                 $templateFactory,
                 $message,
                 $senderResolver,
                 $objectManager,
                 $mailTransportFactory,
                 $messageFactory
             );
     
             $this->mimePartFactory    = $mimePartFactory;
             $this->mimeMessageFactory = $mimeMessageFactory;
         }
     
         protected function prepareMessage(): SyncProductPriceCheckTransportBuilder
         {
             parent::prepareMessage();
     
             $mimeMessage = $this->getMimeMessage($this->message);
     
             foreach ($this->parts as $part) {
                 $mimeMessage->addPart($part);
             }
     
             $this->message->setBody($mimeMessage);
     
             return $this;
         }
     
         public function addAttachment(
             $body,
             $filename = null,
             $mimeType = Mime::TYPE_OCTETSTREAM,
             $disposition = Mime::DISPOSITION_ATTACHMENT,
             $encoding = Mime::ENCODING_BASE64
         ): SyncProductPriceCheckTransportBuilder
         {
             $this->parts[] = $this->createMimePart($body, $mimeType, $disposition, $encoding, $filename);
             return $this;
         }
     
         private function createMimePart(
             $content,
             $type = Mime::TYPE_OCTETSTREAM,
             $disposition = Mime::DISPOSITION_ATTACHMENT,
             $encoding = Mime::ENCODING_BASE64,
             $filename = null
         ): MimePart
         {
             $mimePart = $this->mimePartFactory->create(['content' => $content]);
             $mimePart->setType($type);
             $mimePart->setDisposition($disposition);
             $mimePart->setEncoding($encoding);
     
             if ($filename) {
                 $mimePart->setFileName($filename);
             }
     
             return $mimePart;
         }
     
         private function getMimeMessage(MessageInterface $message): MimeMessage
         {
             $body = $message->getBody();
     
             if ($body instanceof MimeMessage) {
                 return $body;
             }
     
             $mimeMessage = $this->mimeMessageFactory->create();
     
             if ($body) {
                 $mimePart = $this->createMimePart($body, Mime::TYPE_TEXT, Mime::DISPOSITION_INLINE);
                 $mimeMessage->setParts([$mimePart]);
             }
     
             return $mimeMessage;
         }
     }
     ```

##### 常见问题

一般改动了以来注入相关内容（自动注入的构造函数，di.xml）等需要重新编译，改动了 grid 相关内容，需要刷新缓存或清空 ui_bookmark，只改动类中方法可以直接替换。

###### 实例化失败

1. 检查 di.xml 中 preference 中实际类型的参数是否在 di.xml 中声明
2. 清除 var/cache、generated 目录

###### 代码逻辑不执行

如果定义了且引入了文件但未执行，可能是未编译的原因，重新编译

###### 导航栏丢失

商店页面没有 navigation 相关 html 元素

使用虚拟机且 base_url 为 http 时可能出现，在  `design/frontend/*/Magento_Theme/layout/default.xml` 新增布局

```xml
<referenceBlock name="store.menu">
    <block class="Magento\Theme\Block\Html\Topmenu" name="catalog.topnav.fix" template="Magento_Theme::html/topmenu.phtml" before="-"/>
</referenceBlock>
```

###### 属性无法保存提示 definer 不存在

原因 magento 创建了很多的触发器用于在特定的表保存时触发，如果使用 magento 安装程序不会出现错误（此时会以命令行指定的数据库用户创建对应的触发器）。而如果使用别人导出的数据库则可能发生这种情况，此时别人的数据库中触发器 definer 为别人的数据库用户。所以执行属性保存时会报错类似

```
SQLSTATE[HY000]: General error: 1449 The user specified as a definer ('root'@'%') does not exist, query was: UPDATE `catalog_product_entity` SET ...
```

解决有多种方案：

* 建立一个报错中缺失的用户

* 在数据库中修改 triggers 定义，修改其 Definer 为当前数据库用户

  ```mysql
  # 对应 sql 语句
  DROP TRIGGER `trg_cataloginventory_stock_item_after_insert`;
  CREATE DEFINER=`debian-sys-maint`@`%` TRIGGER `trg_cataloginventory_stock_item_after_insert` AFTER INSERT ON `cataloginventory_stock_item` FOR EACH ROW BEGIN
  INSERT IGNORE INTO `scconnector_google_feed_cl` (`entity_id`) VALUES (NEW.`product_id`);
  END;
  ```

* 导出时忽略 definer（5.7 以上）

  ```bash
  mysqlpump --uuser -p --skip-definer database_name > dump_without_definer.sql;
  mysql --uuser -p database_name < dump_without_definer.sql;
  ```

* 导出时替换

  ```bash
  mysqldump -h <database host> --user=<database username> --password=<password> --single-transaction <database name>  | sed -e 's/DEFINER[ ]*=[ ]*[^*]*\*/\*/' | gzip > /tmp/database_no-definer.sql.gz
  ```

###### 管理后台 grid 列表筛选缓慢

* 原因

  旧版本在模块的 Block 目录中使用 Grid 类渲染，此时存在 massaction 的操作会将所有的 id 传入 grid 页面，传输及筛选都很耗时

* 解决方案

  使用 ui_comment 组件替换 Grid 类，此时存在 massaction 的操作全选只渲染当前显示页面 id，对于全选只传递一个标识

##### tips

###### 显示相关

* `module/view/adminhtml/ui_component/*.xml` 中元素的 `<label translats>` 值为前台页面上显示的值
* `module/etc/acl.xml` 中 resource 元素的 translate 为管理后台 System 下 Role Resource 下显示的内容
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
* php7.2 及以上，需要以下扩展（bcmath、ctype、curl、dom、gd、hash、iconv、intl、mbstring、openssl、pdo_mysql、simplexml、soap、xsl、zip）

###### 获取 magento2

使用 git 和 composer 获取 magento 源码或插件时需要进行 magento markplace 身份验证。推荐在当前用户下创建 .composer 运行时目录放入 auth.json，在 marketplace.magento.com 注册并创建 access key，public key 为授权 username，private key 为授权 password，可以写入 php 镜像系统变量

```json
// ~/.composer/auth.json
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

composer（需要身份认证）

会验证 access key，这里安装默认会创建子目录 magento2 需要在 nginx 中修改相应的 $MATE_ROOT

```shell
composer create-project --repository=https://repo.magento.com/ magento/project-community-edition magento2
```

使用 gitee 镜像仓库克隆或在官网下载打包文件

##### 安装及配置

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

###### 配置

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

###### 缓存

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

#### 开发

magento 应用由模块（实现自定义业务逻辑，改变 magento 行为）、主题（前台和后台页面风格与设计）、语言包（本地化相关）组成，构建模块时，必须同时符合 magento 模块标准和 composer pacakge 标准

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

|               文件               | 作用 |
| :------------------------------: | :--: |
|         app/etc/acl.xml          |      |
|        app/etc/config.xml        |      |
|       app/etc/crontabl.xml       |      |
|      app/etc/db_schema.xml       |      |
| app/etc/db_schema_whitelist.json |      |
|          app/etc/di.xml          |      |
| app/etc/extension_attributes.xml |      |
|        app/etc/module.xml        |      |
|     app/etc/{customize}.xml      |      |
|     app/etc/{customize}.xsd      |      |
|        app/etc/webapi.xml        |      |

子配置文件目录，其作用域为特定作用域，会覆盖对应作用域的全局配置。

|        子目录         |      作用域      |
| :-------------------: | :--------------: |
|  app/etc/adminhtml/*  |       后台       |
|  app/etc/frontend/*   |       前台       |
| app/etc/webapi_rest/* |  rest api 接口   |
| app/etc/webapi_soap/* | api 简单对象访问 |
|   app/etc/graphql/*   |     graphql      |

##### 组件开发

开发前需要安装 magento 及其依赖并将其设置为开发者模式。包括布局文件结构，创建必要的配置文件，构建任何所需的 API 接口和服务以及添加组件所需的任何前端部件。构建过程中关闭缓存

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

###### di.xml

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

###### 开发流程

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

命令依赖于 magento 必须安装且配置正确，命令在模块范围内定义。创建命令的流程：

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

   

   


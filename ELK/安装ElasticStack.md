## 安装 Elastic Stack

环境：

ubuntu >= 16.04, jdk8

### 安装 JDK-8
```
sudo add-apt-repository -y ppa:webupd8team/java
sudo apt-get update
echo debconf shared/accepted-oracle-license-v1-1 select true | sudo debconf-set-selections
echo debconf shared/accepted-oracle-license-v1-1 seen true | sudo debconf-set-selections
sudo apt-get -y install oracle-java8-installer
```
### 安装 ElasticSearch

#### 安装 ElasticSearch

* 导入 Elasticsearch PGP key

  `wget -qO - https://artifacts.elastic.co/GPG-KEY-elasticsearch | sudo apt-key add -`

* 从 APT 存储库安装

  `sudo apt-get install apt-transport-https`

  `echo "deb https://artifacts.elastic.co/packages/6.x/apt stable main" | sudo tee -a /etc/apt/sources.list.d/elastic-6.x.list`

  `sudo apt-get update && sudo apt-get install elasticsearch`

#### 配置 ElasticSearch

* 开机自启动

  `sudo /bin/systemctl daemon-reload`

  `sudo  /bin/systemctl enable elasticsearch.service`

* 设置 elasticsearch 自动重启

   `sudo systemctl edit elasticsearch.service` (先设置默认编辑器为 vim)
   ```
   [Service]        // 修改service 配置
   Restart=always
   ```
   `systemctl daemon-reload`   

* 启动及停止命令

  `sudo systemctl start elasticsearch.service`

  `sudo systemctl stop elasticsearch.service`

* 设置 `jvm` 运行内存

   `/etc/elasticseach/jvm.options` 文件的 `-Xms4g` 和 `-Xmx4g`

* 设置 `elasticsearch` 参照 https://www.elastic.co/guide/en/elasticsearch/reference/6.2/important-settings.html，默认开箱即用

#### 安装 ElasticSearch 中文分词工具

`./bin/elasticsearch-plugin install https://github.com/medcl/elasticsearch-analysis-ik/releases/download/v6.2.4/elasticsearch-analysis-ik-6.2.4.zip`

#### ElasticSearch 软件位置

|  type   |                         description                          |          default location          |     setting      |
| :-----: | :----------------------------------------------------------: | :--------------------------------: | :--------------: |
|  home   |          elasticsearch home diretory or `$ES_HOME`           |     `/usr/share/elasticsearch`     |                  |
|   bin   | 执行文件包含 elasticsearch 启动和 elasticsearch-plugin 安装插件 |   `/usr/share/elasticsearch/bin`   |                  |
|  conf   |                    elasticsearch 配置文件                    |        `/etc/elasticsearch`        | `ES_PATCH_CONGF` |
|  conf   | Environment variables including heap size, file descriptors. |    `/etc/default/elasticsearch`    |                  |
|  data   | The location of the data files of each index / shard allocated on the node. Can hold multiple locations |      `/var/lib/elasticsearch`      |   `path.data`    |
|  logs   |                      Log files location                      |      `/var/log/elasticsearch`      |    `path.log`    |
| plugins | Plugin files location. Each plugin will be contained in a subdirectory | `/usr/share/elasticsearch/plugins` |                  |
|  repo   | Shared file system repository locations. Can hold multiple locations. A file system repository can be placed in to any subdirectory of any directory specified here. |           Not configured           |   `path.repo`    |

#### 安装 X-PACK  (6.4) 及以后不再需要安装该工具，已整合

* 安装 X-PACK `sudo /usr/share/elasticsearch/bin/elasticsearch-plugin install x-pack`
* 配置（默认配置）

### 安装 kibana

#### 安装 kibana

* 如果安装 elastic search  时候添加了 key 与 APT 存储库，则直接 `sudo apt-get update && sudo apt-get install kibana`

#### 配置并运行 kibana

* 开机自启动

  `sudo /bin/systemctl daemon-reload`

  `sudo /bin/systemctl enable kibana.service`

* 启动及关闭 kibana

  `sudo systemctl start kibana.service`

  `sudo systemctl stop kibana.service`

#### kibana 目录

|     type     |                         description                          |       default location       |   setting   |
| :----------: | :----------------------------------------------------------: | :--------------------------: | :---------: |
|     home     |           Kibana home directory or `$KIBANA_HOME`            |     `/usr/share/kibana`      |             |
|     bin      | Binary scripts including `kibana` to start the Kibana server and `kibana-plugin` to install plugins |   ` /usr/share/kibana/bin`   |             |
|    config    |          Configuration files including `kibana.yml`          |        `/etc/kibana`         |             |
|     data     | The location of the data files written to disk by Kibana and its plugins |      `/var/lib/kibana`       | `path.data` |
| **optimize** | Transpiled source code. Certain administrative actions (e.g. plugin install) result in the source code being retranspiled on the fly. | `/usr/share/kibana/optimize` |             |
| **plugins**  | Plugin files location. Each plugin will be contained in a subdirectory | `/usr/share/kibana/plugins`  |             |

#### 配置

https://www.elastic.co/guide/en/kibana/6.2/settings.html

#### 安装 X-PACK

`/usr/share/kibana/bin/kibana-plugin install x-pack`

#### 安装 Logstash

#### 安装 Logstash

* 如果已经添加了 GPG-KEY 和 APT 仓库则直接

  `sudo apt-get update && sudo apt-get install logstash`

#### 启动及停止运行 Logstash

* `sudo systemctl start logstash.service`

#### 脚本启动及使用

__测试配置文件：__

`bin/logstash -f first-pipeline.conf --config.test_and_exit`

#### 安装 X-PACK:

`/usr/share/Logstash/bin/logstash-plugin install x-pack`

__启动并指定配置：__

`bin/logstash -f first-pipeline.conf --config.reload.automatic`

`--config.reload.automatic` 允许自动重载加载配置，以便在每次修改配置文件时不必停止和重启 Logstash

#### Logstash 目录结构

|   类型   |                     描述                      |           默认位置            |             设置              |
| :------: | :-------------------------------------------: | :---------------------------: | :---------------------------: |
|   home   |                  软件家目录                   |     `/usr/share/logstash`     |                               |
|   bin    |                二进制脚本目录                 |   `/usr/share/logstash/bin`   |                               |
| settings | 配置文件（包含 logstash.yml, 与 jvm.options)  |        `/etc/logstash`        |        `path.settings`        |
|   conf   |             logstash 管道配置文件             | `/etc/logstash/conf.d/*.conf` | `/etc/logstash/pipelines.yml` |
|   logs   |                 日志文件目录                  |      `/var/log/logstash`      |          `path.logs`          |
| plugins  |   本地，非Ruby-Gem 模块文件，仅用于开发环境   | `/usr/share/logstash/plugins` |        `path.plugins`         |
|   data   | logstash 及其插件用于任何持久性需求的数据文件 |      `/var/lib/logstash`      |         ` path.data`          |

#### X-PACK 统一配置

默认情况下, 将启用所有 X 包功能。可以在、和配置文件中启用或禁用特定的 X 包功能。

`elasticsearch.yml` , `kibana.yml` , `logstash.yml`

|            设置            |                            描述                             |
| :------------------------: | :---------------------------------------------------------: |
|   `xpack.graph.enabled`    |      Set to `false` to disable X-Pack graph features.       |
|     `xpack.ml.enabled`     | Set to `false` to disable X-Pack machine learning features. |
| `xpack.monitoring.enabled` |    Set to `false` to disable X-Pack monitoring features.    |
| `xpack.reporting.enabled`  |    Set to `false` to disable X-Pack reporting features.     |
| ` xpack.security.enabled`  |     Set to `false` to disable X-Pack security features.     |
|  `xpack.watcher.enabled`   |             Set to `false` to disable Watcher.              |


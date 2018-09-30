## ElasticSearch 常用插件

#### 中文分词插件 ik

* 安装

  `./bin/elasticsearch-plugin install https://github.com/medcl/elasticsearch-analysis-ik/releases/download/v6.2.4/elasticsearch-analysis-ik-6.2.4.zip`

* 配置

  * 自定义项目 `dic` 字典文件，`UTF-8` 编码格式，一行一个自定义分词

  * 将自定义字典文件放入 `/etc/elasticsearch/analysis-ik` 文件夹，并修改权限为读写

  * 修改 `/etc/elasticsearch/analysis-ik` 文件夹下配置文件 `IKAnalyzer.cfg.xml` 文件的

    ```
    <!--用户可以在这里配置自己的扩展字典 -->
    <entry key="ext_dict">project.dic</entry>
    ```

    字典起始目录位于 `/etc/elasticsearch/analysis-ik` 目录

*  重启后自动生效，如果错误查看日志排错

#### elasticsearch-sql 查询插件

* 安装

  `./bin/elasticsearch-plugin install https://github.com/NLPchina/elasticsearch-sql/releases/download/6.2.4.0/elasticsearch-sql-6.2.4.0.zip`

* 配置 web 站点

  下载 https://github.com/NLPchina/elasticsearch-sql/releases/download/5.4.1.0/es-sql-site-standalone.zip 并解压

  ```
  cd site-server
  npm install express --save
  node node-server.js
  ```

  修改 `elasticsearch.yml` 添加

  ```
  http.cors.enabled: true
  http.cors.allow-origin: "*"
  ```

* 重启后访问，`http:ip:8080`，并修改顶部的地址为 elastic 地址
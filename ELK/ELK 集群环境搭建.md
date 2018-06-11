### ELK 集群环境搭建

#### ELK多节点配置

* 每个节点安装 elk 套件（最低 elasticsearch,可视化需要 x-pack 插件, 多端同步则需要 logstash deng)

* 配置每个节点的 `elasticsearch.yml`

  ```
  # 每个节点的集群名称需要一致
  cluster.name: work
  # 节点名称，每个节点不一样
  node.name: node-1
  # 可访问网络地址，每个节点物理地址
  network.host: 192.168.10.10
  # http 请求端口默认 9200
  http.port: 9200
  # 节点发现网络地址,节点通信端口默认为 9300
  discovery.zen.ping.unicast.hosts: ["host1:9300", "host2:9300"]
  # 可被选举为主节点的数量,为了避免脑裂个数为 master-eligible nodes / 2 + 1 
  discovery.zen.minimum_master_nodes: 2
  # x-pack 配置
  xpack.security.enabled: false
  ```

* 每个节点都要安装分词与 x-pack 插件


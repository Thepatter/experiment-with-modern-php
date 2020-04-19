## ELK 集群环境搭建

### ElasticStack 6.4 以前

*elasticsearch.yml*

```yamm
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

### ElasticStack 6.4 以后

*elasticsearch.yml*

```yaml
cluster.name: <cluster.name>
node.name: <node.name>
path.data: /var/lib/elasticsearch
path.logs: /var/log/elasticsearch
network.host: 0.0.0.0
http.port: 9200
http.cors.enabled: true
http.cors.allow-origin: "*"
# 发现节点
discovery.seed_hosts:
  - <node.ip:port>
  - <node.ip:port>
  - <node.ip:port>
# 可以被选举为主节点的主机
cluster.initial_master_nodes:
  - <node.name>
  - <node.name>
  - <node.name>
```

必须是新启动节点，如果已生成节点后在配置 `discovery.seed_hosts` 则无法发现节点，因为原来的集群 id 不同。删除 `/var/lib/elasticsearch` 中文件即可以


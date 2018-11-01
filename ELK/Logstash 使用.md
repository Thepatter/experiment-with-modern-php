### Logstash 使用

#### 配置文件：

位于 `/etc/logstash` 目录

包含：

`logstash.yml` 包含 Logstash 配置标志。可以在该文件中设置标志，而不是在命令行传递标志。在命令行中设置的任何标志都将覆盖文件中的相应设置

`pipelines.yml`  包含在单个 Logstash 实例中运行多个管线的框架和说明。

`jvm.options`  包含 JVM 配置标志。使用此文件可以设置总堆空间的初始值和最大值。还可以使用此文件设置 Logstash 的区域设置。在单独的行上指定每个标志。

`log4j2.properties`  包含库的默认设置。

`startup.options`  linux 启动选项

包含用于为系统生成适当的启动脚本的脚本所使用的选项。安装 Logstash 包时, 脚本将在安装过程结束时执行, 并使用中指定的设置设置选项, 如用户、组、服务名称和服务说明。默认情况下, Logstash 服务安装在用户下。该文件使您可以更轻松地安装 Logstash 服务的多个实例。您可以复制该文件并更改特定设置的值。请注意, 在启动时不会读取该文件。如果要更改 Logstash 启动脚本 (例如, 要更改 Logstash 用户或从其他配置路径读取), 则必须重新运行脚本 (作为 root) 以传入新设置

#### logstash.yml 配置

|              设置               |                             描述                             |                            默认值                            |
| :-----------------------------: | :----------------------------------------------------------: | :----------------------------------------------------------: |
|           `node.name`           |                       节点的描述性名称                       |                         机器的主机名                         |
|           `path.data`           |          Logstash 及其插件用于任何持久性需求的目录           |                     `LOGSTASH_HOME/data`                     |
|          `pipeline.id`          |                          管道的 ID                           |                            `main`                            |
|       `pipeline.workers`        | 并行执行管道过滤器和输出阶段的工作人员数量。如果发现事件正在备份，或者CPU未饱和，请考虑增加此数量以更好地利用机器处理能力 |                     默认为 CPU 核心数据                      |
|      `pipeline.batch.size`      | 在尝试执行过滤器和输出之前，单个工作线程从输入收集的最大事件数量。较大的批量大小通常效率更高，但会以增加的内存开销为代价。需要增加 `jvm.options` 配置文件中的 JVM 堆空间 |                             125                              |
|     `pipeline.batch.delay`      | 在创建管道事件批次时，在将不足量的批次发送给管道工作人员之前，等待每个事件需要多长时间（毫秒） |                              50                              |
|   `pipeline.unsafe_shutdown`    | 设置`true`为时，即使在内存中仍存在飞行中事件时，也会强制Logstash在关机期间退出。默认情况下，Logstash将拒绝退出，直到所有收到的事件都被推送到输出。启用此选项可能导致关机期间数据丢失。 |                            false                             |
|          `path.config`          | 主管道的 `Logstash` 配置路径。 如果您指定目录或通配符，则会按字母顺序从目录中读取配置文件。 |                          特定于平台                          |
|         `config.string`         | ‎一个字符串, 包含用于主管线的管线配置。使用与配置文件相同的语法。 |                             没有                             |
|     `config.test_and_exit`      | 设置为时`true`，检查配置是否有效，然后退出。请注意，使用此设置不会检查grok图案的正确性。Logstash可以从目录读取多个配置文件。如果您将此设置与`log.level: debug`Logstash 结合使用，则Logstash会记录组合配置文件，并为每个配置块注释源自它的源文件 |                            false                             |
|     `config.reload.automat`     | 设置 true 时，定期检查配置是否已更改，并在配置发生更改时重新加载配置。这也可以通过SIGHUP信号手动触发 |                            false                             |
|     `config.reload.interva`     |             Logstash在几秒钟内检查配置文件的更改             |                              3s                              |
|         `config.debug`          | 设置为时`true`，将完全编译的配置显示为调试日志消息。你还必须设置`log.level: debug`。警告：日志消息将包括任何以明文形式传递给插件配置的*密码*选项，并可能导致明文密码出现在您的日志中 |                            false                             |
|    `config.support_escapes`     | 设置`true`为时，带引号的字符串将处理以下转义序列：`\n`成为文字换行符（ASCII 10）。`\r`成为一个文字回车（ASCII 13）。`\t`成为一个文字标签（ASCII 9）。`\\`成为文字反斜杠`\`。`\"`成为一个文字双引号。`\'`成为一个文字引号 |                            false                             |
|            `modules`            |   配置时，`modules`必须位于本表上面描述的嵌套YAML结构中。    |                             没有                             |
|          `queue.type`           | 用于事件缓冲的内部排队模型。指定`memory`传统基于内存的排队或`persisted`基于磁盘的确认排队（[持久队列](https://www.elastic.co/guide/en/logstash/current/persistent-queues.html)） |                           `memory`                           |
|          `path.queue`           | 启用持续队列时数据文件存储的目录路径（`queue.type: persisted`）。 |                      ` path.data/queue`                      |
|      `queue.page_capacity`      | 启用持久队列时使用的页面数据文件的大小（`queue.type: persisted`）。队列数据由分离为页面的仅附加数据文件组成。 |                             64mb                             |
|        `queue.max_event`        | 启用持续队列时，队列中未读事件的最大数量（`queue.type: persisted`）。 |                          0（无限）                           |
|        `queue.max_bytes`        | 队列的总容量（以字节数为单位）。确保磁盘驱动器的容量大于您在此处指定的值。如果同时`queue.max_events`和`queue.max_bytes`指定，Logstash采用的是先达到标准 |                         1024mb（1g）                         |
|     `queue.checkpoint.acks`     | 启用持续队列时强制检查点之前的最大确认事件数（`queue.type: persisted`）。指定`queue.checkpoint.acks: 0`将此值设置为无限制 |                             1024                             |
|    `queue.checkpoint.writes`    | 启用持久队列时强制检查点之前写入事件的最大数量（`queue.type: persisted`）。指定`queue.checkpoint.writes: 0`将此值设置为无限制 |                             1024                             |
|          `queue_drain`          |       启用后，Logstash会在关闭之前等待持续队列被排空。       |                                                              |
|   `dead_letter_queue.enable`    |           指示Logstash启用插件支持的DLQ功能的标志            |                            false                             |
| ` dead_letter_queue.max_bytes ` | 每个死信队列的最大大小。如果这些条目超出此设置将增加死信队列的大小，则条目将被丢弃 |                            1024mb                            |
|    `path.dead_letter_queue`     |             数据文件将存储在死信队列中的目录路径             |                `path.data/dead_letter_queue`                 |
|           `http.host`           |                   指标REST端点的绑定地址。                   |                       ` "127.0.0.1" `                        |
|           `http.port`           |                    指标REST端点的绑定端口                    |                             9600                             |
|          ` log.level`           | 日志级别。有效的选项是：`fatal`  `error`  `warn`  `info`  `debug`  `trace` |                             info                             |
|          `log.format`           | 日志格式。设置为`json`登录JSON格式，或`plain`使用`Object#.inspect` |                            plain                             |
|           `path.logs`           |                 Logstash将其日志写入的目录。                 |                      LOGSTASH_HOME/logs                      |
|         `path.plugins`          | 在哪里可以找到自定义插件。您可以多次指定此设置以包含多个路径。插件预计将在一个特定的目录层次结构：`PATH/logstash/TYPE/NAME.rb`其中`TYPE`是`inputs`，`filters`，`outputs`，或`codecs`，并且`NAME`是插件的名称 | 特定于平台的。请参阅[Logstash目录布局](https://www.elastic.co/guide/en/logstash/current/dir-layout.html)。 |

### 使用：

#### 从命令行运行 Logstash

`bin/logstash [option]` 在命令行设置的标志会覆盖 `logstash.yml` 中的设置，但不会改变文件






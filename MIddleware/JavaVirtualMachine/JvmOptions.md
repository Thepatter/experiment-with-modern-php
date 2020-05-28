### Jvm 运行时或调优时各种参数

#### 内存相关

|             选项             |                             含义                             |
| :--------------------------: | :----------------------------------------------------------: |
|            `-Xms`            |                          设置最小堆                          |
|            `-Xmx`            |                          设置最大堆                          |
|            `-Xmn`            |                          新生代大小                          |
| `-XX:PretenureSizeThreshold` | 指定大于该设置值的对象直接在老年代分配，避免在 Eden 区以及 Survivor 区之间来回复制，产生大量的内存复制操作（只对 Serial 和 ParNew 两款新生代收集器有效） |
|  `-XX:MaxTenuringThreshold`  |                  对象晋升到老年代的年龄阈值                  |
| `-XX:HandlePromotionFailure` |                       设置是否允许冒险                       |
|      `-XX:MaxPermSize`       |            设置永久代最大容量（jdk8之前需要设置）            |
|                              |                                                              |



|          选项          |            含义            |                             影响                             | jdk  |
| :--------------------: | :------------------------: | :----------------------------------------------------------: | :--: |
| `-XX:+UseCondCardMark` | 是否开启卡表更新的条件判断 | 开启会增加一次额外判断的开销，但能够避免伪共享问题，两者各有性能损耗 |  7   |
|                        |                            |                                                              |      |
|                        |                            |                                                              |      |
|                        |                            |                                                              |      |
|                        |                            |                                                              |      |

##### 垃圾收集

|           选项            |          含义          | 影响 |     jdk      |
| :-----------------------: | :--------------------: | :--: | :----------: |
| `-XX:+UseConcMarkSweepGC` |  激活 CMS 垃圾收集器   |      |      5       |
|   `-XX:+/-UseParNewGC`    | 启用 ParNew 垃圾收集器 |      | 9 取消该参数 |
|  `-XX:ParallelGCThreads`  |  限制垃圾收集的线程数  |      |              |

###### ParallelScavenge

|             选项             |                             含义                             |
| :--------------------------: | :----------------------------------------------------------: |
|    `-XX:MaxGCPauseMillis`    | ParallelScavenge 垃圾收集器的最大垃圾收集停顿时间（大于 0 的毫秒数） |
|      `-XX:GCTimeRatio`       | ParallerScavenge 垃圾收集器的吞吐量大小（0 -100 的整数，默认 99 即允许最大 1% 的垃圾收集时间）垃圾收集时间占总时间的比率 |
| `-XX:+UseAdaptiveSizePolicy` | 激活时不需要人工指定新生代的大小（-Xmn）、Eden与Survivor 区的比例（-XX:SurvivorRatio）、晋升老年代对象大小（-XX:PretenureSizeThreshold），虚拟机会根据运行情况动态调整 |

###### Concurrent Mark Sweep

|                 选项                 |                             含义                             |
| :----------------------------------: | :----------------------------------------------------------: |
| `-XX:GMSInitiatingOccupancyFraction` |            提高 CMS 的触发百分比降低内存回收频率             |
| `-XX:+UseCMSCompactAtFullCollection` | 默认开启，jdk 9 废弃，用于在 CMS 收集器不得不进行 Full GC 时开启内存碎片的合并整理过程 |
|   `-XX:CMSFullGCsBeforeCompaction`   | jdk 9 废弃，要求 CMS 在执行若干次（数量由参数值决定，默认为 0）不整理空间的 FullGC 之后，下一次进入 FullGC 前会先进行碎片整理 |
|      `-XX:+UseConcMarkSweepGC`       |              开启 GMC 收集器，Jdk 9 废弃1` wQA               |

###### GarbageFirst

|          选项          |                      含义                      |
| :--------------------: | :--------------------------------------------: |
| `-XX:MaxGCPauseMillis` |           允许停顿时间，默认 200 秒            |
| `-XX:G1HeapRegionSize` | 设置堆中 Region 区域大小 1 ~ 32 M，2 的 N 次幂 |
|                        |                                                |


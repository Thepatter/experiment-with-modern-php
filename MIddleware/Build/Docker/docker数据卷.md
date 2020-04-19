### docker 数据卷

是一个可供容器使用的特殊目录，它将主机操作系统目录直接映射进容器，类似于 linux 中的 mount 行为，有用的特性：

* 数据卷可以在容器之间共享和重用，容器间传递数据将变得高效与方便
* 对数据卷内数据的修改会立马生效，无论是容器内操作还是本地操作
* 对数据卷的更新不会影响镜像，解耦开应用和数据
* 卷会一直存在，直到没有容器使用，可以安全地卸载它

#### 创建数据卷

使用 volume 子命令来管理数据卷

```shell
# 创建数据卷 test，指定 local 驱动
docker volume create -d local test
# 查看信息
docker volume inspect test
# 列出所有卷
docker volume ls
# 清理无用卷
docker volume prune
# 删除无用卷
docker volume rm
```

默认情况下，Docker 创建新卷时采用内置的 local 驱动，本地卷只能被所在节点的容器使用，使用 -d 指定不同的驱动。使用local驱动创建的卷在Docker主机上均有其专属目录，在Linux中位于/var/lib/docker/volumes目录下，在Windows中位于C:\ProgramData\Docker\volumes目录下。这意味着可以在Docker主机文件系统中查看卷，甚至在Docker主机中对其进行读取数据或者写入数据操作

#### 绑定数据卷

可以在创建容器时将主机本地的任意路径挂载到容器内作为数据卷，即绑定数据卷，在用 `docker [container] run` 命令时，可以使用 `-mount` 选项来使用数据卷。`-mount` 选项支持三种类型的数据卷，包括：

* `-volume`

  普通数据卷，映射到主机 `/var/lib/docker/volumes` 路径下

* `-bind`

  绑定数据卷，映射到主机指定路径下

* `-tmpfs`

  临时数据卷，只存在于内存中

```shell
docker run -d -P --name web --mount type=bind,source=/webapp,destination=/opt/webapp training/webapp python app.py
# 上述等价于旧的 -v 标记
docker run -d -P --name web -v /webapp:/opt/webapp training/webapp python app.py
```

本地目录的路径必须是绝对路径，容器内路径可以为相对路径，如果目录不存在，docker 会自动创建，docker 挂载数据卷的默认权限是读写（rw），可通过 `ro` 指定为只读（容器内对锁挂载数据卷内的数据就无法修改了）

```shell
docker run -d -P --name web -v /webapp:/opt/webapp:ro training/webapp python app.py
```

如果直接挂载一个文件到容器，使用文件编辑工具，可能回造成文件 `inode` 的改变，1.1.0 开始，这会导致错误信息，推荐的方式是直接挂载目录到容器中

#### 数据卷容器

需要在多个容器之间共享一些持续更新的数据，最简单的方式是使用数据卷容器，它专门提供数据卷给其他容器挂载。

```shell
# 创建一个数据卷容器 dbdata, 在其中创建一个数据卷挂载到 /dbdata
docker run -it -v /dbdata --name dbdata ubuntu
# 在其他容器中使用
docker run -it --volumes-from dbdata --name db1 ubuntu
```

使用 `--volumes-from` 参数所挂载数据卷的容器自身并不需要保持在运行状态。如果删除了挂载的容器，数据卷并不会被自动删除，如果要删除一个数据卷，必须在删除最后一个还挂载着它的容器时显式使用 `docker rm -v` 命令来指定同时删除关联的容器。

##### 利用数据卷容器来迁移数据

可以利用数据卷容器对其中的数据卷进行备份、恢复、以实现数据的迁移

* 备份

  ```shell
  # 备份 dbdata 数据卷容器内的数据卷
  docker run --volumes-from dbdata -v $(pwd):/backup --name worker ubuntu tar cvf /backup/backup.tar /dbdata
  ```

  使用 Ubuntu 镜像创建一个容器 worker，使用 `--volumes-from dbdata` 参数来让 worker 容器挂载 dbdata 容器的数据卷（即 dbdata 数据卷）；使用 `-v $(pwd):/backup` 参数来挂载本地的当前目录到 worker 容器的 `/backup` 目录。worker 容器启动后，使用 `tar cvf/backup/backup.tar /dbdata` 命令将 `/dbdata` 下内容备份为容器内的 `/backup/backup.tar`，即宿主机当前目录下的 backup.tar

* 恢复数据到一个容器

  ```shell
  # 创建一个带有数据卷的容器 dbdata2:
  docker run -v /dbdata --name dbdata2 ubuntu /bin/bash
  # 创建一个新的容器，挂载 dbdata2 容器，并解压备份文件到所挂载的容器卷中
  docker run --volumes-from dbdata2 -v $(pwd):/backup busybox tar xvf /backup/backup.tar
  ```

  

  


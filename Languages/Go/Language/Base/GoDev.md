### Go 运行相关

#### Go 环境变量

Go 开发环境依赖于一些操作系统环境变量

* `$GOROOT`

  表示 Go 在主机的安装位置，一般 `$HOME/go`

* `$GOARCH`

  表示目标机器的处理器架构

* `$GOOS`

  表示目标机器的操作系统

* `$GOBIN`

  表示编译器和链接器的安装位置，默认是 `$GOROOT/bin`

* `$GOPATH`

  默认采用和 `$GOROOT` 一样的值，从 1.1 版本开始，必须修改为其他路径。它可以包含多个包含 Go 语言源码文件，包文件和可执行文件的路径。

#### 安装

##### Linux 下安装

* 下载源码

* 配置相关变量

  ```shell
  # 设置 Go 环境变量
  export GOROOT=$HOME/go
  # 相关文件在文件系统的任何地方都能被调用
  export PATH=$PATH:$GOROOT/bin
  # 保存你的工作目录
  export GOPATH=$HOME/Applications/Go
  ```

* 安装 C 工具

  ```shell
  sudo apt-get install bison ed gawk gcc libc6-dev make
  ```

* 安装目录清单

  ```shell
  /bin 		// 包含可执行文件：编译器，Go 工具
  /doc		// 示例程序，本地文档
  /lib		// 库
  /misc		// 支持 Go 编辑器有关的配置文件以及
  /os_arch    // 包含标准库的包的对象文件
  /src		// 源码
  /src/cmd	// Go 和 C 的编译器和命令行脚本
  ```

  


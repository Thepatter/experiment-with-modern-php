### maven 构建配置文件

构建配置文件是一系列的配置项的值，用来设置或者覆盖 maven 构建默认值。使用构建配置文件，可以为不同的环境，定制构建方式。配置文件在 `pom.xml` 文件中使用 `activeProfiles` 或者 `profiles` 元素指定，并且可以通过各种方式触发。配置文件在构建时修改 `POM`，并且用来给参数设定不同的目标环境。

#### 构建配置文件的类型

* 项目级

  定义在项目的 `POM` 文件  `pom.xml` 中

* 用户级

  定义在 Maven 的设置 xml 文件中（`%USER_HOME%/.m2/settings.xml`)

* 全局

  定义在 Maven 全局的设置 xml 文件中（`%M2_HOME%/conf/settings.xml`）

### mvn 仓库

在 maven 的术语中，仓库是一个位置（place）。maven 仓库是项目中依赖的第三方库，这个库所在的位置即仓库，在 maven 中，任何一个依赖、插件或者项目构建的输出，都可以称之为构件。maven 仓库能帮助我们管理构件（主要是 JAR），它是放置所有 JAR 文件（WAR，ZIP，POM）的地方。maven 仓库有三种类型：

* 本地（local）

  maven 的本地仓库，在安装 maven 后并不会创建，它是在第一次执行 maven 命令的时候才被创建。运行 maven 的时候，maven 所需要的任何构件都是直接从本地仓库获取的。如果本地仓库没有，它会首先尝试从远程仓库下载构件至本地仓库，然后再使用本地仓库的构件，默认情况下，每个用户在自己的用户目录下都有一个路径名为 `.m2/respository/` 的仓库目录。要修改默认位置，在 `%MAVEN_HOME%/conf` 目录中的 `settings.xml` 文件中定义另一个路径

  ```xml
  <settings xmlns="http://maven.apache.org/SETTINGS/1.0.0"
      xsmls:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://maven.apache.org/SETTINGS/1.0.0
      http://maven.apache.org/xsd/settings-1.0.0.xsd">
      <localRepository>/user/local/repository</localRepository>
  </settings>
  ```

* 中央（central）

  maven 中央仓库是由 maven 社区提供的仓库，其中包含了大量常用的库，包含了绝大多数流行的开源 Java 构件，这个仓库由 maven 社区管理，不需要配置，需要通过网络才能访问

* 远程（remote）

  如果 maven 在中央仓库中找不到依赖的文件，它会停止构件过程并输出错误信息到控制台，为避免这种情况，maven 提供了远程仓库概念，它是开发人员自己定制的仓库，包含了所需要的代码库或者其他工程中用到的 jar 文件。

当执行 maven 构建命令时，maven 会在本地仓库中搜索，如果找不到，则在中央仓库中搜索，如果找不到，并且有一个或多个远程仓库已经设置，在一个或多个远程仓库中搜索依赖文件。

#### 配置仓库镜像

##### 统一修改仓库地址

可以直接修改 `MAVEN_HOME/conf` 文件夹中的 `settings.xml` 文件，或者 `~/.m2/settings.xml` 文件。`setting.xml` 里有个 `mirrors` 节点，用来配置镜像 URL。mirrors 可以配置多个 mirror，每个 mirror 有：

* id

  唯一标识一个 mirror

* name

  类似描述

* `url`

  官方库地址

* `mirrorOf`

  代表一个镜像的替代位置，central 即代替官方的中央库

`mirror` 不是按 `settings.xml` 中书写的顺序进行查询，会按 `id` 的字母排序来进行查找。

```xml
<mirrors>
	<mirror>
        <id>alimaven</id>
        <name>aliyun maven mirror</name>
        <url>https://maven.aliyun.com/repository/central</url>
        <mirrorOf>central</mirrorOf>
    </mirror>
</mirrors>
```

##### 单个项目

直接在项目的 `pom.xml` 中修改中央库的地址

```xml
<repositories>
    <repository>
        <id>alimaven</id>
        <name>aliyun maven</name>
        <url>https://maven.aliyun.com/repository/central</url>
    </repository>
</repositories>
```


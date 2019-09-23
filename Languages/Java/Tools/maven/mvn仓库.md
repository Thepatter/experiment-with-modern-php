### mavn 仓库

#### 概述

在 maven 世界中，任何一个依赖，插件或者项目构建的输出，都可以称为构件。任何一个构件都有一组坐标唯一标识。得益于坐标机制，任何 maven 项目使用任何一个构件的方式都是完全相同的。在此基础上，maven 可以在某个位置统一存储所有 maven 项目共享的构件，这个统一的位置就是仓库。实际的 maven 项目将不再各自存储其依赖文件，它们只需要声明这些依赖的坐标，在需要的时候 maven 会自动根据坐标找到仓库中的构件，并使用它们。项目构建完毕后生成的构件也可以安装或者部署到仓库中，供其他项目使用

#### 仓库布局

任何一个构件都有其唯一的坐标，根据这个坐标可以定义其在仓库中的唯一存储路径，即 maven 的仓库布局方式。路径与坐标的大致对应关系为 `groupId/artifactId/version/artifactId-version.packagin`，maven 仓库是基于简单文件系统存储的。

#### 仓库分类

仓库分为本地仓库和远程仓库。当 maven 根据坐标寻找构件的时候，它首先会查看本地仓库，如果本地仓库存在此构件，则直接使用；如果本地仓库不存在此构件，或者需要查看是否有更新的构件版本，maven 就会去远程仓库查找，发现需要的构件之后，下载到本地仓库再使用。如果本地和远程都没有需要的构件 maven 会报错。

* 中央仓库

  中央仓库是 maven 核心自带的远程仓库，包含了绝大部分开源的构件。在默认配置下，当本地仓库 maven 需要构件的时候，它就会尝试从中央仓库下载

* 本地仓库

  一般来说，在 maven 项目目录下，没有诸如 `lib/` 这样用来存放依赖文件的目录，当 maven 在执行编译或测试时，如果需要使用依赖文件，它总是基于坐标使用本地仓库的依赖文件。默认情况下，每个用户在自己的用户目录下都有一个路径为 `.m2/repository/` 的仓库目录。可以编译 `.m2/settings.xml`，设置 `localRepository` 元素的值为想要的仓库地址

  ```xml
  <settings>
  	<localRepository>D:\java\repository\</localRepository>
  </settings>
  ```

##### 远程仓库配置

在默认中央仓库无法满足项目的需要，可能项目需要的构件存在于另一个远程仓库中，这时，可以在 POM 中配置该仓库

```xml
<project>
	<repositories>
  	    <respository>
            <id>jboss</id>
            <name>JBoss Repository</name>
            <url>http://repository.jboss.com/maven2/</url>
            <releases>
                <enabled>true</enabled>
            </releases>
            <snapshots>
                <enabled>false</enabled>
            </snapshots>
            <layout>default</layout>
        </respository>
    </repositories>
</project>
```

在 repositories 元素下，可以使用 repository 子元素声明一个或多个远程仓库。任何一个仓库声明的 id 必须是唯一的。maven 自带的中央仓库 id 为 central，如果其他的仓库声明也使用该 id，就会覆盖中央仓库的配置。releases 和 snapshots 元素用来控制 maven 对于发布版构件和快照版构件的下载。除了 enabled，还包含两个子元素 `updatePolicy`（配置 maven 从远程仓库检查更新的频率，默认值是 daily 每天，可用值：never 从不，always 每次构建，`interval：x` 每隔 x 分钟检查一次） 和 `checksumPolicy`（配置 maven 检查检验和文件的策略。当构件被部署到 maven 仓库中时，会同时部署对应的校验和文件。在下载构件的时候，maven 会验证校验和文件，当值为默认的 warn 时，maven 会在执行构建时输出警告信息，fail 遇到校验和错误就构建失败，ignore 使 maven 完全忽略校验和错误）。layout 元素值表示仓库的布局是 `maven2` 或 `maven3` 的默认布局，而不是 `maven1` 的布局。

* 远程仓库的认证

  大部分远程仓库无须认证就可以访问，当需要认证时，配置认证信息必须在 `settings.xml` 文件中

  ```xml
  <settings>
  	<servers>
    	    <server>
      	    <id>my-proj</id>
              <username>repo-user</username>
              <password>repo-pwd</password>
          </server>
      </servers>
  </settings>
  ```

  maven 使用 servers 元素及其 server 子元素配置仓库认证信息。id 元素必须与 POM 中需要认证的 repository 元素的 id 完全一致。

* 部署到远程仓库

  在 POM 中配置构件部署地址

  ```xml
  <project>
      <distributionManagement>
          <repository>
              <id>proj-release</id>
              <name>Proj Release Repository</name>
              <url>http://192.168.1.100/content/repositories/proj-releases</url>
          </repository>
          <snapshotRepository>
              <id>proj-snapshots</id>
              <name>Proj Snapshot Repository</name>
              <url>http://192.168.1.100/content/repositories/proj-snapshots</url>
          </snapshotRepository>
      </distributionManagement>
  </project>
  ```

  `distributionManagement` 包含 repository 和 `snapshotRepository` 子元素，前者表示发布版本构件的仓库，后者表示快照版本的仓库。配置正确后，运行：`man clean deploy` 命令，maven 就会将项目构建输出的构件部署到配置对应的远程仓库，如果项目当前的版本是快照版本，则部署到快照版本仓库地址，否则部署到发布版本仓库地址

##### 配置仓库镜像

统一修改仓库地址

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

单个项目

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

#### 仓库搜索

* `Sonatype Nexus`

  `http://repository.sonatype.org`

* `jarvana`

  `http://www.jarvana.com/jarvana/`

* `MVNbbrowser`

  `http://www.mvnbrowser.com`

* `MVNrepository`

  `http://mvnrepository.com`

  
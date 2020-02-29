## maven 构建

### maven 构建配置文件 profile

为了能让构建在各个环境下方便的移植，maven 引入了 profile 的概念。profile 能够在构建的时候修改 POM 的一个子集，或者添加额外的配置元素。用户可以使用很多方式激活 profile，以实现构建在不同环境下的移植，构建配置文件是一系列的配置项的值，用来设置或者覆盖 maven 构建默认值。使用构建配置文件，可以为不同的环境，定制构建方式。配置文件在 `pom.xml` 文件中使用 `activeProfiles` 或者 `profiles` 元素指定，并且可以通过各种方式触发。配置文件在构建时修改 `POM`，并且用来给参数设定不同的目标环境。

#### 构建配置文件的类型

* 项目级

  定义在项目的 `POM` 文件  `pom.xml` 中

* 用户级

  定义在 Maven 的设置 xml 文件中（`%USER_HOME%/.m2/settings.xml`)

* 全局

  定义在 Maven 全局的设置 xml 文件中（`%M2_HOME%/conf/settings.xml`）

*pom文件中profile能使用元素*

```xml
<project>
	<repositories></repositories>
    <pluginRepositories></pluginRepositories>
    <distributionManagement></distributionManagement>
    <dependencies></dependencies>
    <dependencyManagement></dependencyManagement>
    <modules></modules>
    <properties></properties>
    <reporting></reporting>
    <build>
    	<plugins></plugins>
        <defaultGoal></defaultGoal>
        <resources></resources>
        <testResources></testResources>
        <finalName></finalName>
    </build>
</project>
```

*pom外部文件中profile可用元素*

```xml
<project>
	<repositories></repositories>
    <pluginRepositories></pluginRepositories>
    <properties></properties>
</project>
```

#### 激活 profile

- 默认激活

  在定义 profile 的时候指定其默认激活，使用 `activeByDefault` 元素用户可以指定 profile 自动激活。如果 POM 中任何一个 profile 通过其他任意一种方式被激活了，所有的默认激活配置都会失效

  ```xml
  <profiles>
  	<profile>
          <id>dev</id>
          <activation>
          	<activeByDefault>true</activeByDefault>
          </activation>
      </profile>
  </profiles>
  ```

  如果项目中有很多的 profile，它们的激活方式各异，`maven-help-plugin` 可以帮助用户了解当前激活的 `profile`

  ```shell
  # 当前激活的 profile
  mvn help:active-profiles
  # 列出当前所有的 profile
  mvn help:all-profiles
  ```

- 命令行激活

  可以使用 maven 命令行参数 -P 加 profile 的 id 来激活，多个 id 之间用 ，分割

  ```shell
  mvn clean install -Pdev, test
  ```

- settings 文件显式激活

  可以配置 `settings.xml` 文件的  `activeProfiles` 元素，表示其配置的 `profile` 对于所有项目都处于激活状态

  ```xml
  <settings>
  	<activeProfiles>
          <activeProfile>dev</activeProfile>
      </activeProfiles>
  </settings>
  ```

- 系统属性激活

  可以配置当某系统属性存在的时候且值确定时，自动激活 profile

  ```xml
  <profiles>
  	<profile>
      	<activation>
          	<property>
              	<name>test</name>
                  <value>x</value>
              </property>
          </activation>
      </profile>
  </profiles>
  ```

  ```shell
  mvn clean install -Dtest = x
  ```

- 操作系统环境激活

  profile 可以自动根据操作系统环境激活，如果构建在不同的操作系统有差异，用户完全可以将这些差异写进 profile，然后配置它们自动基于操作系统环境激活

  ```xml
  <profiles>
  	<profile>
      	<activation>
          	<os>
                  <name>Windows XP</name>
                  <family>Windows</family>
                  <arch>x86</arch>
                  <version>5.1.2600</version>
              </os>
          </activation>
      </profile>
  </profiles>
  ```

### maven 生命周期

maven 生命周期包含了项目的清理、初始化、编译、测试、打包、集成测试、验证、部署、站点生成等几乎所有构建步骤。maven 的生命周期是抽象了构建的各个步骤，定义了它们的次序，生命周期本身不做任何实际的工作。在 maven 的设计中，实际的任务都交由插件来完成，maven 为大多数构建步骤编写并绑定了默认插件。

##### 三套生命周期

maven 拥有三套相互独立的生命周期：clean、default、site。clean 生命周期的目的是清理项目，default 生命周期的目的是构建项目，而 site 生命周期的目的是建立项目站点。每个生命周期包含一些阶段（phase），这些阶段是有顺序的，并且后面的阶段依赖于前面的阶段，执行阶段会自动执行之前的阶段，用户和 maven 最直接的交互方式就是调用这些生命周期阶段。三套生命周期本身是相互独立的，用户可以仅仅调用某个生命周期的某个阶段。

clean 生命周期

* `pre-clean`

  执行一些清理前需要完成的工作

* clean

  清理上一次构建生成的文件

* post-clean

  执行一些清理后需要完成的工作

default 生命周期

* validate

* initialize

* generate-source

* process-source

  处理项目主资源文件，一般是对 `src/main/resources` 目录的内容进行变量替换等工作后，复制到项目输出的主 `classpath` 目录中

* generate-resource

* process-resources

* compile

  编译项目的主源码，一般是编译 `src/main/java` 目录下的 Java 文件至项目输出的主 `classpath` 目录中

* process-classes

* generate-test-sources

* process-test-sources

  处理项目测试资源文件，一般是对 `src/test/resources` 目录的内容进行变量替换等工作后，复制到项目输出的测试 `classpath` 目录中

* generate-test-resources

* process-test-resources

* test-compile

  编译项目的测试代码，一般是编译 `src/test/java` 目录下的 Java 文件至项目输出的测试 `classpath` 目录中

* process-test-classes

* test

  使用单元测试框架运行测试，测试代码不会被打包或部署

* prepare-package

* package

  打包成可发布的格式

* `pre-integration-test`

* integration-test

* post-integration-test

* verify

* install

  将包安装到 maven 本地仓库，供本地其他 maven 项目使用

* deploy

  将最终的包复制到远程仓库，供其他 maven 项目使用

site 生命周期

* `pre-site`

  执行一些在生成项目站点之前需要完成的工作

* `site`

  生成项目站点文档

* `post-site`

  执行一些在生成项目站点之后需要完成的工作

* `site-deploy`

  将生成的项目站点发布到服务器上

##### 自定义绑定

除了内置绑定，用户还能够自己选择将某个插件目标绑定到生命周期的某个阶段上，这种自定义绑定方式能让 maven 项目在构建过程中执行自定任务。

```xml
<build>
	<plugins>
    	<plugin>
        	<groupId>org.apache.maven.plugins</groupId>
            <artifactId>maven-source-plugin</artifactId>
            <version>2.1.1</version>
            <executions>
            	<exectution>
                    <id>attach-sources</id>
                    <phase>verify</phase>
                    <goals>
                        <goal>jar-no-fork</goal>
                    </goals>
                </exectution>
            </executions>
        </plugin>
    </plugins>
</build>
```

`<execution>` 用来配置执行一个任务，phase 指定生命周期阶段，goals 配置指定要执行的插件目标。当多个目标被绑定到同一阶段，这些插件声明的先后顺序决定了目标的执行顺序。很多插件的目标在编写时已经定义了默认绑定阶段。可以使用 `maven-help-plugin` 查看插件详细信息。

```shell
# 获取插件详细信息可以省略版本
mvn help:describe-Dplugin = org.apache.maven.plugins:maven-source-plugin:2.1.1-Ddetail
# 使用插件目标前缀代替坐标
mvn help:describe-Dplugin = compiler
# 插件目标信息
mvn help:describe-Dplugin = compiler-Dgoal = compile
# 插件详情
mvn help:describe-Dplugin = compiler-Ddetail
```

Bound to phase 会显示该目标默认绑定的生命周期阶段

### 插件配置

* 命令行插件配置

  很多插件目标的参数都支持从命令行配置，用户可以在 maven 命令中使用 -D 参数，并指定 key=value 的形式来配置插件目标的参数

  ```shell
  # 跳过执行测试
  mvn install -Dmaven.test.skip = true
  ```

* POM 中插件全局配置

  可以在声明插件的时候，对此插件进行一个全局配置。所有基于该插件目标的任务，都会使用这样配置

  ```xml
  <build>
      <plugins>
          <plugin>
              <groupId>org.apache.maven.plugins</groupId>
              <artifactId>maven-compile-plugin</artifactId>
              <version>2.1</version>
              <configuration>
              	<source>11</source>
                  <target>11</target>
              </configuration>
      	</plugin>
      </plugins>
  </build>
  ```

* POM 中插件任务配置

  可以为某个插件任务配置特定的参数。让 maven 在不同的生命阶段执行不同的任务

  ```xml
  <build>
      <plugins>
          <plugin>
              <groupId>org.apache.maven.plugins</groupId>
              <artifactId>maven-antrun-plugin</artifactId>
              <version>1.3</version>
              <executions>
              	  <execution>
                      <id>ant-validate</id>
                      <phase>validate</phase>
                      <goals>
                      	<goal>run</goal>
                      </goals>
                      <configuration>
                      	<tasks>
                              <echo>I'm bound to validate phase</echo>
                          </tasks>
                      </configuration>
                  </execution>
              </executions>
          </plugin>
      </plugins>
  </build>
  ```

  `configuration` 元素位于 execution 元素下，即特定任务的配置，位于 `plugin` 元素下，即为插件整体配置

##### 插件仓库

与依赖构件一样，插件构件同样基于坐标存储在 maven 仓库中，在需要的时候，maven 会从本地仓库寻找插件，如果插件不存在，则从远程仓库查找，找到插件后，再下载到本地仓库使用。maven 会区别对待依赖的远程仓库和插件的远程仓库，插件的远程仓库使用 `pluginRepositories` 和 `pluginRepository` 配置

```xml
<pluginRepositories>
	<pluginRepository>
    	<id>central</id>
        <name>maven plugin repository</name>
        <url>http://repo1.maven.org/maven2</url>
        <layout>default</layout>
        <snapshots>
        	<enabled>false</enabled>
        </snapshots>
        <releases>
        	<updatePolicy>never</updatePolicy>
        </releases>
    </pluginRepository>
</pluginRepositories>
```

在 POM 中配置插件的时候，如果该插件是  maven 的官方插件（`groupId = org.apache.maven.plugins`），就可以省略 `groupId` 配置

```xml
<build>
	<plugins>
    	<plugin>
        	<artifactId>maven-compiler-plugin</artifactId>
            <version>2.1</version>
            <configuration>
            	<source>11</source>
                <target>11</target>
            </configuration>
        </plugin>
    </plugins>
</build>
```

###### 导入 jar 到本地仓库

```shell
mvn install:install-file -Dfile=/path/to/ojdbc8.jar -DgroupId=com.oracle -DartifactId=oracle -Dversion=8.0.0 -Dpackaging=jar
```

* -Dfile：文件位置
* -DgroupId：依赖的 groupId
* -DartifactId：依赖的 artifactId
* -Dversion：依赖版本号
* -Dpacking：类型

### maven 构建 web 应用

#### 概述

需要显式指定打包方式为 `war`，maven 对 web 项目的布局结构也有一个通用的约定：

* 类及资源文件与 Jar 项目一致

* Web 资源目录

  默认位置为 `/src/main/webapp/`，必须包含一个子目录 WEB-INF，该子目录还必须包含 `web.xml` 文件。它们与 WAR 包中的 Web 资源完全一致。WAR 包中有一个 lib 目录包含所有依赖 JAR 包，但 maven 项目结构中没有这样一个目录，maven 在用 WAR 方式打包的时候会根据 POM 的配置从本地仓库复制相应的 JAR 文件

```xml
<artifactId>account-web</artifactId>
<packaging>war</packaging>
<!-- 使用 finalName 元素指定 war 包名称 -->
<build>
	<finalName>account-web</finalName>
</build>
```

使用 profile 过滤 web 资源，配置不同 profile

```xml
<profiles>
	<profile>
        <id>client-a</id>
        <proerties>
        	<client.logo>a.jpg</client.logo>
            <client.theme>red</client.theme>
        </proerties>
    </profile>
    <profile>
    	<id>client-b</id>
        <properties>
        	<client.logo>b.jpg</client.logo>
            <client.theme>blue</client.theme>
        </properties>
    </profile>
</profiles>
```

使用 `maven-war-plugin` 插件对 `src/main/webapp` 资源目录开启过滤

```xml
<plugin>
	<groupId>org.apache.maven.plugins</groupId>
    <artifactId>maven-war-plugin</artifactId>
    <version>2.1-beta-1</version>
    <configuration>
    	<webResources>
        	<resource>
            	<filtering>true</filtering>
                <directory>src/main/webapp</directory>
                <includes>
                	<include>**/*.css</include>
                    <include>**/*.js</include>
                </includes>
            </resource>
        </webResources>
    </configuration>
</plugin>
```

激活构建 `mvn clean install -Pclient-a`

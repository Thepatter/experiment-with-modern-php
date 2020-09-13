### maven

#### 介绍

##### 安装与配置

###### 安装

1. 安装 java
2. 下载 maven
3. 配置系统变量 M2_HOME 为 mvn 解压目录
4. 将 M2_HOME/bin 添加到系统 path

###### *M2_HOME*

* *bin*

  包含 mvn 运行的脚本，这些脚本用来配置 java 命令，准备好 classpath 和相关的 java 系统属性，然后执行 java 命令。

* boot

  类加载器框架

* conf

  包含全局配置的 settings.xml 文件，修改该文件会对影响全局 mavn 的行为，可以将该文件复制到 ～/.m2/ 下在用户范围定制 maven 行为

* Lib

  包含所有 maven 运行时需要的 java 类库，maven 本身是分模块开发的

###### *~/.m2*

用户 mvn 目录，包含 mvn 本地仓库 repository，用户下载的所有的 maven 构件存储到该目录

###### 约定目录

基于项目对象模型概览，可以对 java 项目进行构建、依赖管理。Maven 提倡约定优于配置

*maven约定目录结构*

|                目录                |                  目的                  |
| :--------------------------------: | :------------------------------------: |
|             ${basedir}             |       存放 pom.xml 和所有子目录        |
|      ${basedir}/src/main/java      |           项目的 java 源代码           |
|   ${basedir}/src/main/resources    |      项目的资源，如 property 文件      |
|      ${basedir}/src/test/java      |              项目的测试类              |
|    ${basedir}/src/test/resource    |              测试用的资源              |
| ${basedir}/src/main/webapp/WEB-INF |     Web 应用文件目录，web 项目信息     |
|         ${basedir}/target          |              打包输出目录              |
|     ${basedir}/target/classes      |              编译输出目录              |
|   ${basedir}/target/test-classes   |            测试编译输出目录            |
|             Test.java              | mvn 只会自动运行符合该命名规则的测试类 |
|         ～/.m2/repository          |            mvn 本地仓库目录            |

#### 构建项目

##### 基础配置

###### 基础 pom 文件

```xml
<?xml version="1.0" encoding="UTF-8"?>
<project xmlns="http://maven.apache.org/POM/4.0.0"
         xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:schemaLocation="http://maven.apache.org/POM/4.0.0     
                             http://maven.apache.org/xsd/maven-4.0.0.xsd">
    <modelVersion>4.0.0</modelVersion>
    <!-- 配置 java 版本编码等属性，等价于下面 build 插件作用 -->
    <properties>
      	<project.build.sourceEncoding>UTF-8</project.build.sourceEncoding>
      	<maven.compiler.encoding>UTF-8</maven.compiler.encoding>
      	<java.version>11</java.version>
      	<maven.compiler.source>11</maven.compiler.source>
      	<maven.compiler.target>11</maven.compiler.target>
    </properties>
  
    <groupId>com.local.product</groupId>
    <artifactId>base</artifactId>
    <version>1.0-SNAPSHOT</version>
    <name>this is test project</name>
  
  	<build>
      <plugins>
          <plugin>
              <groupId>org.apache.maven.plugins</groupId>
          	  <artifactId>maven-compiler-plugin</artifactId>
              <configuration>
            	  <source>11</source>
            	  <target>11</target>
          	  </configuration>
          </plugin>
          <plugin>
              <groupId>org.apache.maven.plugins</groupId>
              <artifactId>maven-resources-plugin</artifactId> 
              <configuration>
                  <encoding>UTF-8</encoding>
              </configuration>
          </plugin>
          <!-- 配置 jar 入口 -->
          <plugin>
              <groupId>org.springframework.boot</groupId>
              <artifactId>spring-boot-maven-plugin</artifactId>
              <configuration>
                  <mainClass>com.example.business.Application</mainClass>
              </configuration>
          </plugin>
      </plugins>
    </build>
</project>
```

###### super pom

是 maven 的默认 pom，所有的 pom 都继承自一个父 POM（无论是否显式定义了这个父 POM）。父 POM 包含了一些可以被继承的默认设置。当 maven 需要下载 pom 中的依赖时，它会到 super pom 中定义的默认仓库下载

```shell
# 查看 super pom 默认配置
mvn help:effective-pom
```

###### Archetype 生成骨架

```shell
# mvn 3
mvn archetype:generate
# mvn 2
mvn org.apache.maven.plugins:maven-archetype-plugin:2.0-alpha-5:generate
```

有几种 Archetype 供选择，默认 maven-archetype-quickstart，接着会提示输入要创建项目的 groupId、artifactId、version、以及包名 package。

Archetype 插件将根据提供的信息创建项目骨架。会创建对应的目录结构及一个包含 main 方法的 java 类，和 pom 文件

可以使用 -B 选项来以批处理方式运行

```shell
mvn archetype:generate -B \
-DarchetypeGroupId = org.apache.maven.archetypes \
-DarchetypeArtifactId = maven-archetype-quickstart \
-DarchetypeVersion = 1.0 \
-DgroupId = com.web.action \
-DartifactId = archetype -test \
-Dversion = 1.0-SNAPSHOT \
-Dpackage = com.test.action
```

常用的 Archetype

* maven-archetype-quickstart

  默认值，生成内容包含：

  * 一个包含 JUnit 依赖声明的 pom.xml
  * src/main/java 主代码目录及该代码目录下一个名为 App 的输出 hello world 的类
  * src/test/java 测试代码目录及该目录下一个名为 AppTest 的 JUnit 测试用例

* maven-archetype-webapp

  简单 maven war 项目模板：

  * 一个 packaging 为 war 且带有 JUnit 依赖声明的 pom.xml
  * src/main/webapp 目录
  * src/main/webapp/index.jsp 文件，一个简单的 Hello World 页面
  * src/main/webapp/WEB-INF/web.xml，一个基本为空的 Web 应用配置文件

###### 坐标

Maven 使用坐标标识构建，坐标的元素包括 groupId、artifactId、version、packaging、classifier。groupId、artifactId、version 必须定义的，packaging 可选，classifier 不能直接定义的。项目构件的文件名是与坐标相对应的，一般的规则为 artifactId-version [-classifier].packaging。maven 仓库布局也是基于 maven 坐标。

* groupId

  定义当前 maven 项目隶属的实际项目。maven 项目和实际项目不一定是一对一关系。groupId 不应该对应项目隶属的组织或公司。groupId 的表示方式与 java 包名的表示方式类似，通常与域名反向一一对应。

  阿里 java 规范指定：com.{公司/BU}.业务线 [.子业务线]，最多 4 级

* artifactId

  该元素定义实际项目中的一个 maven 项目（模块），推荐的做法是使用实际项目名称作为 artifactId 的前缀。在默认情况下，maven 生成的构件，其文件名会以 artifactId 作为开头

  阿里 java 规范指定：产品线名-模块名。语义不重复，不遗漏

* version

  该元素定义 maven 项目当前所处的版本

  阿里 java 规范指定：

  1. 主版本号：产品方向改变，或者大规模 API 不兼容，或者架构不兼容升级
  2. 次版本号：保持相对兼容性，增加主要功能特性，影响范围极小的 API 不兼容修改
  3. 修订版本号：保持完成兼容，修复 BUF、新增次要功能特性

  版本后缀 snapshot 代表不稳定，尚处于开发中的版本，release 代表发布的稳定版本

  协同开发时，如果 A 依赖构件 B，由于 B 会更新，B 应该使用 SNAPSHOT 来标识自己。如果 B 不用 SNAPSHOT，而是每次更新后都使用一个稳定的版本，那版本号就会升的太快，对版本号造成滥用；

  如果 B 不用 SNAPSHOT，但是一直使用一个单一的 Release 版本号，那当 B 更新后，A 可能并不会接受到更新，因为 A 使用的 repository 一般不会频繁更新 release 版本的缓冲（本地 repository），所以 B 以不换版本号的方式更新后，A 在拿 B 时发现本地已有这个版本，就不会去远程 repository 下载最新的 B。正式环境中不得使用 snapshot 版本的库。

* packaging

  该元素定义 maven 项目的打包方式，打包方式通常与所生成构件的文件扩展名对应，打包方式会影响到构建的生命周期，jar 打包和 war 打包会使用不同的命令。当未定义 packaging 的时候，maven 使用默认值 jar。支持 war、pom、maven-plugin、ear 等

* classifier

  该元素用来定义构建输出的一些附属构建。附属构建与主构件对应。不能直接定义项目的 classifier，因为附属构件不是项目直接默认生成的，而是由附加的插件帮助生成

##### 依赖

根元素 project 下的 dependencies 可以包含一个或多个 dependency 元素，以声明一个或多个项目依赖。

```xml
<dependencies>
    <dependency>
      	<groupId>junit</groupId>
      	<artifactId>junit</artifactId>
      	<version>4.7</version>
      	<!-- 默认值为 compile，该依赖对主代码和测试代码都有效 -->
      	<scope>test</scope>
    </dependency>
</dependencies>
```

每个依赖可以包含的元素：

* groupId、artifactId、version

  依赖的基本坐标，对于任何一个依赖来说，基本坐标是最重要的，maven 根据坐标才能找到需要的依赖

* type

  依赖的类型，对应于项目坐标定义的 packaing，大部分情况下，该元素不必声明，其默认值为 jar

* scope

  范围

* optional

  标记依赖是否可选

* exclusions

  排除依赖传递性依赖


###### 依赖范围

Maven 在编译、测试、运行时会使用不同的 classpath，依赖范围就是用来控制三种 classpath 的关系，maven 有以下几种依赖范围：

* compile

  编译依赖范围。如果没有指定，就会默认使用该依赖范围。使用此依赖范围的 maven 依赖，对于编译、测试、运行三种 classpath 都有效。

* test

  测试依赖范围。使用此依赖范围的 maven 依赖，只对于测试 classpath 有效，在编译主代码或者运行项目时将无法使用此类依赖。

* provided

  已提供依赖范围。使用此依赖范围的 maven 依赖，对于编译和测试 classpath 有效，但在运行时无效（如 servlet-api，编译和测试项目时需要，但运行项目时，由于容器已经提供，则不需要 maven 重复引入)

* runtime

  运行时依赖范围。使用此依赖范围的 maven 依赖，对于测试和运行 classpath 有效，但在编译主代码时无效（如 JDBC 驱动实现，项目主代码的编译只需要 jdk 提供的 JDBC 接口，只有在执行测试或运行项目时才需要实现上述接口的具体 JDBC 驱动）

* system

  系统依赖范围。和 provided 依赖范围完全一致。但是，使用 system 范围的依赖时必须通过 systemPath 元素显式地指定依赖文件的路径。由于此类依赖不能通过 maven 仓库解析，而且往往与本机系统绑定，可能造成构建的不可移植，因谨慎使用。systemPath 元素可以引用环境变量

  ```xml
  <scope>system</scope>
  <systemPath>${java.home}/lib/rt.jar</systemPath>
  ```

* import

  (maven 2.0.9 及以上)，导入依赖范围。该依赖范围不会对三种 classpath 产生实际的影响

*依赖范围与 classpath 关系*

| 依赖范围 scope | 对于编译 classpath 有效 | 对于测试 classpath 有效 | 对于运行时 classpath 有效 |              eg              |
| :------------: | :---------------------: | :---------------------: | :-----------------------: | :--------------------------: |
|    compile     |            Y            |            Y            |             Y             |         spring-core          |
|      test      |            N            |            Y            |             N             |            JUnit             |
|    provided    |            Y            |            Y            |             N             |         servlet-api          |
|    runtime     |            N            |            Y            |             Y             |             JDBC             |
|     system     |            Y            |            Y            |             N             | 本地的，mvn 仓库之外的库文件 |

###### 依赖传递

Account-mail 有一个 compile 范围的 spring-core 依赖，spring-core 有一个 compile 范围的 commons-logging 依赖，那么 commons-logging 就会成为 account-email 的 compile 范围依赖，commons-logging 是 account-email 的一个传递性依赖。maven 会解析各个直接依赖的 POM，将那些必要的间接依赖，以传递性依赖的形式引入到当前的项目中

依赖范围不仅可以控制依赖与三种 classpath 的关系，还对传递性依赖产生影响。假设 A 依赖于 B，B 依赖于 C，A 对于 B 是第一直接依赖，B 对于 C 是第二直接依赖，A 对于 C 是传递性依赖。第一直接依赖的范围和第二直接依赖的范围决定了传递性依赖的范围。

*依赖范围影响传递性依赖，左边第一行直接依赖范围，上面第一列为第二直接依赖范围，交叉为传递性依赖范围*

|          | compile  | test | provided | runtime  |
| :------: | :------: | :--: | :------: | :------: |
| compile  | compile  |      |          | runtime  |
|   test   |   test   |      |          |   test   |
| provided | provided |      | provided | provided |
| runtime  | runtime  |      |          | runtime  |

* 当第二直接依赖的范围是 compile 的时候，传递性依赖的范围与第一直接依赖的范围一致；
* 当第二直接依赖的范围是 test 的时候，依赖不会得以传递；
* 当第二直接依赖的范围是 provided 的时候，只传递第一直接依赖范围也为 provided 的依赖，且传递性的范围同样为 provided；
* 当第二直接依赖的范围是 runtime 的时候，传递性依赖的范围与第一直接依赖的范围一致，但 compile 例外，此时传递性依赖的范围为 runtime

maven 引入的传递性依赖机制，大部分情况下只需要关心项目的直接依赖是什么，而不用考虑这些直接依赖会引入什么传递性依赖。

项目 A 有以下依赖关系 A->B->C->X(1.0) A->D->X(2.0)，X 是 A 的传递性依赖，但是两条路径上有两个版本的 X，此时 maven 会根据依赖调解机制：

* 路径最近者优先；
* 在路径长度相等的前提下，在 POM 中依赖声明的顺序决定，顺序最靠前的那个依赖优先解析；

###### 可选依赖

项目 A 依赖于项目 B，项目 B 依赖于项目 X 和 Y，B 对于 X 和 Y 的依赖都是可选依赖：A->b、b->X（可选）、b->Y（可选）。根据传递性依赖的定义，如果所有这三个依赖的范围都是 compile，那么 X、Y 就是 A 的 compile 范围传递性依赖。由于 X、Y 为可选依赖，依赖将不会传递

可选依赖指项目支持多种特性，且这些特性是互斥的，用户只会使用单个特性。

在理想情况下，是不应该使用可选依赖的。

###### 排除依赖

传递依赖会给项目隐式引入很多依赖。当前项目有一个第三方依赖，而这个第三方依赖了另外一个类库的 SNAPSHOT 版本，那么这个 SNASHOT 就会成为当前项目的传递性依赖，而 SNAPSHOT 的不稳定性会直接影响到当前项目。这时需要排除该 SNAPSHOT，并且在当前项目中声明该类库的某个正式发布的版本。

代码中使用 exclusions 元素声明排除依赖，exclusions 可以包含一个或多个 exclusion 子元素，可以排除一个或多个传递性依赖。声明 exclusion 时只需要 groupId 和 artifactId，而不需要 version 元素。

###### 归类依赖

假定依赖于同一项目的不同模块，这些依赖的版本都是相同的，如果将来需要升级，这些依赖的版本会一起升级。如（springframework），可以在一个唯一的地方定义版本，并且在 dependency 声明中引用。使用 properties 元素定义 maven 属性

```xml
<properties>
	<springframework.version>4.3.18</springframework.version>
</properties>
<dependencies>
	<dependency>
        <groupId>org.springframework</groupId>
        <artifactId>spring-beans</artifactId>
        <version>${springframwrok.version}</version>
    </dependency>
</dependencies>
```

###### 优化依赖

maven 会自动解析所有项目的直接依赖和传递性依赖，并且根据规则正确判断每个依赖的范围，对于一些依赖冲突，也能进行调节，以确保任何一个构件只有唯一的版本在依赖中存在。这些工作后，得到的依赖称为已解析依赖（Resolved Dependency）。

```shell
# 查看当前项目的已解析依赖
mvn dependency:list
# 查看当前项目的依赖树
mvn dependency:tree
# 分析依赖
mvn dependency:analyze
```

分析依赖结果中：

* Used undeclared dependencies found

  为项目中使用到的，但是没有显式声明的依赖，这种依赖意味着潜在的风险，当前项目直接在项目中使用它们，应该显式声明任何项目中直接用到的依赖

* Unused declared dependencies found

  为项目中未使用的，但显式声明的依赖，对于这一类依赖，不应该简单地直接删除其声明，而是应该仔细分析，由于 dependency:analyze 只会分析编译主代码和测试代码需要用到的依赖，一些执行测试和运行时需要的依赖它就发现不了。

##### 属性

###### 自定义属性

通过 properties 元素可以自定义一个或多个 maven 属性，然后在 POM 的其他地方使用 ${属性名} 的方式引用该属性，可以消除重复

###### 内置属性

* ${basedir}

  项目根目录

* ${version}

  项目版本

###### POM 属性

使用该属性引用 POM 文件中对应元素的值，常用 POM 属性包括

* ${project.build.sourceDirectory}

  项目的主源码目录，默认为 src/main/java

* ${project.build.testSourceDirectory}

  项目的测试源码目录，默认为 src/test/java

* ${project.build.directory}

  项目构建输出目录，默认为 target/

* ${project.outputDirectory}

  项目主代码编译输出目录，默认为 target/classes/

* ${project.testOutputDirectory}

  项目测试代码编译输出目录，默认为 target/test-classes/

* ${project.groupId}

  项目的 groupId

* ${project.artifactId}

  项目的 artifactId

* ${project.verion}

  项目的 version，等价 ${version}

* ${project.build.finalName}

  项目打包输出文件的名称，默认为 artifactId-version

###### settings 属性

使用该属性引入 settings. 开头的属性，引用 settings.xml 文件中 XML 元素的值

* ${settings.localRepository}

  指定本地仓库地址

###### java 系统属性

所有 java 系统属性都可以使用 maven 属性引用，使用 mvn help:system 查看所有 java 系统属性

###### 环境变量属性

所有环境变量可以使用以 ${evn.属性名} 形式引用

##### profiles

###### 构建文件 profile

为了能让构建在各个环境下方便移植，maven 引入了 profile 的概念。profile 能够在构建的时候修改 POM 的一个子集，或者添加额外的配置元素，用户可以使用很多方式激活 profile，以实现构建在不同环境下的移植。

构建配置文件是一系列的配置项的值，用来设置或者覆盖 maven 构建默认值。使用构建配置文件，可以为不同的环境，定制构建方式。配置文件在 POM 文件中使用 activeProfiles 或者 profiles 元素指定，并且可以通过各种方式触发。配置文件在构建时修改 POM，并且用来给参数设定不同的目标环境

```shell
# 查看当前激活 profile
mvn help:active-profiles
# 列出所有 profile
mvn help:all-profiles
```

可以在以下位置声明 profile

* POM 文件，声明的 profile 只对当前项目有效

  支持：repositories、pluginsRepositories、distributionManagement、dependencies、dependencyManagement、modules、properties、reporting、build 元素

* 用户 settings.xml，对该用户所有的 maven 项目有效

* 全局 settings.xml，对本机所有 maven 项目有效

  setting.xml 中只支持：repositories、pluginRepositories、properties

###### 激活 profile

* 命令行构建时使用 -P 参数指定 profile 对应 id 后激活，多个 id 之间用逗号分隔

* settings 文件显式激活

  如果希望某个 profile 默认一直处于激活状态，可以配置 settings.xml 的 activeProfile，表示其配置的 profile 对于所有项目都处于激活状态

* 系统属性激活，配置当某系统属性存在的时候，自动激活 profile

  ```xml
  <profiles>
      <profile>
          <artivation>
              <property>
                  <name>test</name>
                  <value>x</value>
              </property>
          </artivation>
    	</profile>
  </profiles>
  ```

  ```xml
  # 声明系统属性
  mvn clean install -Dtest=x
  ```

* 操作系统环境激活，profile 可以自动根据操作系统环境激活

  ```xml
  <activation>
      <os>
        	<!-- name arch version 对应系统属性 os.name、os.arch、os.version -->
        	<name>mac</name>
        	<!-- 包括 Windows、UNIX、Mac -->
        	<family>Mac OS X</family>
        	<arch>x86_64</arch>
        	<version>10.14</version>
      </os>
  </activation>
  ```

* 根据项目中某个文件存在与否来决定是否激活 profile

  ```xml
  <activation>
      <file>
        	<missing>x.properties</missing>
        	<exists>y.properties</exists>
      </file>
  </activation>
  ```

* 默认激活

  ```xml
  <activation>
  	<activeByDefault>true</activeByDefault>
  </activation>
  ```

  优先级最低，如果以其他方式激活了 profile，则默认激活失效

###### 不同构建环境

maven 支持针对不同的环境生成不同的构建

1. 在配置文件中使用 maven 属性标识发生变化的部分

2. 指定不同环境的 profile，并在该 profile 中声明 maven 属性

   ```xml
<profiles>
       <profile>
           <id>dev</id>
           <properties>
               <db.url>jdbc:mysql://127.0.0.1:3306/test</db.url>
           </properties>
     	</profile>
   </profiles>
   ```
   
3. 开启过滤

   maven 属性只在 POM 中才会被解析，使用 maven-resources-plugin 插件过滤并解析

   ```xml
   <resources>
     	<resource>
           <directory>${project.basedir}/src/main/resources</directory>
           <filtering>true</filtering>
     	</resource>
   </resources>
   ```

4. 命令行激活 profile

   ```shell
   mvn clean install -Pdev
   ```

##### 打包项目

###### Shade

该插件用于生成胖 JAR 文件

```xml
</project>
		<build>
      	<plugins>
      			<plugin>
          			<groupId>org.apache.maven.plugins</groupId>
                <artifactId>maven-shade-plugin</artifactId>
                <version>3.1.0</version>
              	<executions>
                		<phase>package</phase>
                    <goals>
                    		<configuration>
                        		<transformers>
                                <!-- 包括了资源转换器 -->
                                <transformer implemetation="org.apache.maven.plugins.shade.resource.ManifestResourceTransformer"></transformer>
            <!-- 指定一个要包含在 JAR 清单中的主类 -->                    <mainClass>com.packege.name.ClassName</mainClass>
                            </transformers>
                        </configuration>
                    </goals>
                </executions>
          	</plugin>
      	</plugins>
		</build>
</project>
```

###### war

```xml
<build>
		<plugins>
  			<plugin>
        		<groupId>org.apache.maven.pluginds</groupId>
            <artifactId>maven-war-plugin</artifactId>
            <version>3.2.0</version>
            <configuration>
            		<archive>
            				<manifest>
                    		<addClasspath>true</addClasspath>
                    </manifest>
                </archive>
            </configuration>
        </plugin>
    </plugins>
</build>
```



#### 仓库

##### 概述

在 maven 世界中，任何一个依赖，插件或者项目构建的输出，都可以称为构件。任何一个构件都有一组坐标唯一标识。得益于坐标机制，任何 maven 项目使用任何一个构件的方式都是完全相同的。在此基础上，maven 可以在某个位置统一存储所有 maven 项目共享的构件，这个统一的位置就是仓库。

实际的 maven 项目将不再各自存储其依赖文件，它们只需要声明这些依赖的坐标，在需要的时候 maven 会自动根据坐标找到仓库中的构件，并使用它们。项目构建完毕后生成的构件也可以安装或者部署到仓库中，供其他项目使用

##### 仓库布局

任何一个构件都有其唯一的坐标，根据这个坐标可以定义其在仓库中的唯一存储路径，即 maven 的仓库布局方式。路径与坐标的大致对应关系为 *groupId/artifactId/version/artifactId-version.packagin*，maven 仓库是基于简单文件系统存储的。对应的路径按如下步骤生成：

1. 基于构件的 groupId 准备路径，将 groupId 中的句点分隔符转换成路径分隔符
2. 基于构件的 artifactId 准备路径，在 groupId 路径基础上加 artifactId 以及一个路径分隔符
3. 使用版本信息，在前面的基础上加 version 和分隔符
4. 如果构件有 classifier，就加上构件分隔符和 classifier
5. 检查构件的 extension，若 extension 存在，则加上句点分隔符和 extension

##### 仓库分类

仓库分为本地仓库和远程仓库。当 maven 根据坐标寻找构件的时候，它首先会查看本地仓库，如果本地仓库存在此构件，则直接使用；如果本地仓库不存在此构件，或者需要查看是否有更新的构件版本，maven 就会去远程仓库查找，发现需要的构件之后，下载到本地仓库再使用。如果本地和远程都没有需要的构件 maven 会报错。

###### 远程仓库

中央仓库是 maven 核心自带的远程仓库，包含了绝大部分开源的构件。在默认配置下，当本地仓库 maven 需要构件的时候，它就会尝试从中央仓库下载。私服是另一种特殊的远程仓库。

对于 maven 来说，每个用户只有一个本地仓库，但可以配置访问很多远程仓库

*配置远程仓库*

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

在 repositories 元素下，可以使用 repository 子元素声明一个或多个远程仓库。任何一个仓库声明的 id 必须是唯一的。maven 自带的中央仓库 id 为 central，如果其他的仓库声明也使用该 id，就会覆盖中央仓库的配置。

releases 和 snapshots 元素用来控制 maven 对于发布版构件和快照版构件的下载：

* enabled

  指定是否启用指定版本的下载

* updatePolicy

  配置 maven 从远程仓库检查更新的频率，默认值是 daily 每天，可用值：never 从不，always 每次构建，interval：x 每隔 x 分钟检查一次

* checksumPolicy

  配置 maven 检查检验和文件的策略。当构件被部署到 maven 仓库中时，会同时部署对应的校验和文件。在下载构件的时候，maven 会验证校验和文件，当值为默认的 warn 时，maven 会在执行构建时输出警告信息，fail 遇到校验和错误就构建失败，ignore 使 maven 完全忽略校验和错误

layout 元素值表示仓库的布局是 maven2 或 maven3 的默认布局，而不是 maven1 的布局。

远程仓库的认证

大部分远程仓库无须认证就可以访问，当需要认证时，配置认证信息必须在 settings.xml 文件中

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

部署到远程仓库

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

distributionManagement 包含 repository 和 snapshotRepository 子元素，前者表示发布版本构件的仓库，后者表示快照版本的仓库。两个元素下都需要配置 id、name、url，id 为该远程仓库唯一标识，url 为仓库地址

配置正确后，运行：man clean deploy 命令，maven 就会将项目构建输出的构件部署到配置对应的远程仓库，如果项目当前的版本是快照版本，则部署到快照版本仓库地址，否则部署到发布版本仓库地址

配置仓库镜像

* 统一修改仓库地址

  可以直接修改 MAVEN_HOME/conf 文件夹中的 settings.xml 文件，或者 ~/.m2/settings.xml 文件。setting.xml里有个 mirrors 节点，用来配置镜像 URL。mirrors 可以配置多个 mirror，每个 mirror 有：

  id：唯一标识一个 mirror，name：类似描述，url：mirror 地址，mirrorOf：代表一个镜像的替代位置，central 即代替官方的中央库

  mirror 不是按 settings.xml 中书写的顺序进行查询，会按 id 的字母排序来进行查找。

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

* 单个项目

  直接在项目的 pom.xml 中修改中央库的地址

  ```xml
  <repositories>
      <repository>
        	<id>alimaven</id>
        	<name>aliyun maven</name>
        	<url>https://maven.aliyun.com/repository/central</url>
      </repository>
  </repositories>
  ```

###### 本地仓库

一般来说，在 maven 项目目录下，没有诸如 *lib/* 这样用来存放依赖文件的目录，当 maven 在执行编译或测试时，如果需要使用依赖文件，它总是基于坐标使用本地仓库的依赖文件。

默认情况下，每个用户在自己的用户目录下都有一个路径为 *.m2/repository/* 的仓库目录。可以编辑 .m2/settings.xml，设置 localRepository 元素的值为想要的仓库地址

```xml
<settings>
	<localRepository>D:\java\repository\</localRepository>
</settings>
```

*   导入 jar 到本地仓库

    ```shell
    # file 文件位置、packing 类型
    mvn install:install-file -Dfile=/path/to/ojdbc8.jar -DgroupId=com.oracle -DartifactId=oracle -Dversion=8.0.0 -Dpackaging=jar
    ```

#### 生命周期

##### 生命周期

maven 的生命周期就是为了对所有的构建过程进行抽象和统一，包含了项目的清理、初始化、编译、测试、打包、集成测试、验证、部署和站点生成等。

生命周期抽象了构建的各个步骤，定义了它们的次序，生命周期本身不做任何实际的工作，实际的任务都交由插件完成，每个构建步骤可以绑定一个或多个插件行为，为大多数构建步骤编写了并绑定了默认插件。

在 maven 的日常使用中，命令行的输入往往对应了生命周期。maven 生命周期是抽象的，其实际行为都由插件来完成。

maven 拥有三套相互独立的生命周期

* clean

  清理项目

* default

  构建项目

* site

  建立项目站点

每个生命周期包含一些阶段，这些阶段是有顺序的，并且后面的阶段依赖于前面的阶段，用户和 maven 直接交互方式就是调用这些生命周期阶段。

三套生命周期本身是相互独立的，用户可以仅调用生命周期的某个阶段，而不会对其他生命周期产生任何影响。

###### clean 生命周期

clean 生命周期的目的是清理项目，包含：

1. Pre-clean 执行清理前需要完成的工作
2. clean 清理上一次构建生成的文件
3. post-clean 执行一些清理后需要完成的工作

###### default 生命周期

default 生命周期定义了真正构建时需要执行的步骤

1. validate

2. initialize

3. generate-sources

4. process-sources

   处理项目主资源文件，一般是对 src/main/resources 目录的内容进行变量替换等工作，复制到项目输入的主 classpath 目录中

5. generate-resources

6. process-resources

7. compile

   编译项目主源码，一般是编译 src/main/java 目录下的 java 文件至项目输出的主 classpath 目录中

8. process-classes

9. generate-test-sources

10. process-test-sources

    处理项目测试资源文件，一般是对 src/test/resources 目录的内容进行变量替换等工作后，复制到项目输出的测试 classpath 目录中

11. generate-test-resources

12. Process-test-resources

13. test-compile

    编译项目的测试代码，一般是编译 src/test/java 目录下的 java 文件至项目输出的测试 classpath 目录中

14. process-test-classes

15. test

    使用单元测试框架进行测试，测试代码不会被打包或部署

16. prepare-package

17. package

    接受编译好的代码，打包成可发布的格式

18. pre-integration-test

19. integration-test

20. post-integration-test

21. verify

22. install

    将包安装到 maven 本地仓库

23. deploy

    将包部署到远程仓库

###### site 生命周期

site 生命周期是建立和发布项目站点。maven 能基于 POM 所包含的信息，自动生成一个友好的站点

1. pre-site

   执行一些在生成项站点之前需要完成的工作

2. site

   生成项目站点文档

3. post-site

   执行一些在生成项目站点之后需要完成的工作

4. site-deloy

   将生成的项目站点发布到服务器上

##### 插件

###### 插件目标

插件以独立的构件形式存在，maven 会在需要的时候下载并使用插件，对于插件本身，为了能够复用代码，它往往能完成多个任务。这些功能聚集在一个插件里，每个功能就是一个插件目标。

通用的写法为冒号前面是插件前缀，冒号后面是插件的目标。

###### 内置插件

maven 生命周期（某个阶段）与插件相互绑定，用以完成实际的构建任务，maven 在核心为一些主要的生命周期阶段绑定了很多插件的目标。

* clean 生命周期

  clean 与 maven-clean-plugin:clean 绑定

* default 生命周期

  由于项目的打包类型会影响构建的具体过程，default 生命周期的阶段与插件目标的绑定由项目打包类型所决定。

  jar 类型内置插件绑定

  |        生命周期        |              插件目标               |            执行任务            |
  | :--------------------: | :---------------------------------: | :----------------------------: |
  |   process-resources    |  maven-resources-plugin:resources   |   复制主资源文件至主输出目录   |
  |        compile         |    maven-compiler-plugin:compile    |     编译主代码至主输出目录     |
  | process-test-resources | maven-compiler-plugin:testResources | 复制测试资源文件至测试输出目录 |
  |      test-compile      |  maven-compiler-plugin:testCompile  |   编译测试代码至测试输出目录   |
  |          test          |     maven-surefire-plugin:test      |          执行测试用例          |
  |        package         |        maven-jar-plugin:jar         |        创建项目 jar 包         |
  |        install         |    maven-install-plugin:install     |  将项目输出构件安装到本地仓库  |
  |         deploy         |     maven-deploy-plugin:deploy      |  将项目输出构件部署到远程仓库  |

* site 生命周期

  site 和 maven-site-plugin:site 绑定，site-deploy 和 maven-site-plugin:deploy 绑定

###### 自定义绑定

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

* execution 用来配置执行一个任务

* phase 指定生命周期阶段

* goals 配置指定要执行的插件目标

  当多个目标被绑定到同一阶段，这些插件声明的先后顺序决定了目标的执行顺序。很多插件的目标在编写时已经定义了默认绑定阶段。可以使用 maven-help-plugin 查看插件详细信息。

```shell
# 获取插件详细信息可以省略版本
mvn help:describe -Dplugin=org.apache.maven.plugins:maven-source-plugin:2.1.1-Ddetail
# 使用插件目标前缀代替坐标
mvn help:describe -Dplugin=compiler
# 插件目标信息
mvn help:describe -Dplugin=compiler -Dgoal=compile
# 插件详情
mvn help:describe -Dplugin=compiler -Ddetail
```

Bound to phase 会显示该目标默认绑定的生命周期阶段

###### 插件配置

用户可以配置插件目标的参数，进一步调整插件目标所执行的任务，几乎所有 maven 插件的目标都有一些可配置的参数，用户可用通过命令行和 POM 配置等方式来配置这些参数

* 命令行插件配置

  很多插件目标的参数都支持从命令行配置，用户可以在 maven 命令中使用 -D 参数，并指定 key=value 的形式来配置插件目标的参数

  ```shell
  # 跳过执行测试
  mvn install -Dmaven.test.skip=true
  ```

* POM 中插件全局配置

  用户可以在声明插件的时候，对此插件进行一个全局配置，即所有该基于该插件目标的任务，都会使用这些配置。如指定使用编译的 java 版本

* POM 中插件任务配置

  除了为插件配置全局参数，还可以为某个插件任务配置特定的参数，让 maven 在不同的生命阶段执行不同的任务

  ```xml
  # 将 maven-antrun-plugin: run 绑定到多个生命周期阶段上
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
                      <!-- configuration在execution为特定任务配置，在plugin为插件整体配置 -->
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

###### maven-help-plugin

可以使用 maven-help-plugin 来获取插件的详细信息。

```shell
mvn help:describe -Dplugin=org.apache.maven.plugins:maven-compiler-plugin
```

返回信息包含坐标，目标前缀（Goal Prefix，其作用是在命令行直接运行插件）

###### 插件仓库

与依赖构件一样，插件构件同样基于坐标存储在 maven 仓库中，在需要的时候，maven 会从本地仓库寻找插件，如果插件不存在，则从远程仓库查找，找到插件后，再下载到本地仓库使用。

maven 会区别对待构件的远程仓库和插件的远程仓库，插件的远程仓库使用 pluginRepositories 和 pluginRepository 配置，除此之外子元素表达的含义与依赖仓库一致

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

在 POM 中配置插件的时候，如果该插件是  maven 的官方插件（groupId = org.apache.maven.plugins），就可以省略 groupId 配置，但不建议使用

###### 插件解析

在用户没有提供插件版本的情况下，maven 会自动解析插件版本。

Maven 在超级 POM 中为所有核心插件设定了版本，超级 POM 是所有 Maven 项目的父 POM，所有项目都继承这个超级 POM 的配置，即使不加任何配置，Maven 使用核心插件的时候，它们的版本就已经确定了

如果用户使用某个插件时没有指定版本，而这个插件又不属于核心插件，maven 就回去检查所有仓库中可用的版本，然后做出选择：

* maven 2 解析机制为，插件的版本会被解析至 latest，该版本可能为 snapshot

* maven 3 解析机制为，当插件没有声明版本时，不再解析至 latest，使用 release

###### 插件管理

maven 提供了 pluginManagement 元素帮助管理插件，在该元素中配置的依赖不会造成实际的插件调用行为，当 POM 中配置了真正的 plugin 元素，并且其 groupId 和 artifactId 与 pluginManagement 中配置的插件匹配时，pluginManagement 的配置才会影响实际的插件行为。

当项目中的多个模块有同样的插件配置时，应当将配置移动道父 POM 的 pluginManagement 元素中。即使各个模块对于同一插件的具体配置不尽相同，也应当使用父 POM 的 pluginManagement 元素统一声明插件的版本。

#### 多模块

多模块构建，使用一条命令构建多个模块。需要创建一个额外的模块，然后通过该模块构建整个项目的所有模块。该模块作为一个聚合项目，聚合模块通常仅包含一个 POM 文件

##### 聚合 pom

```xml
<project>
    <groupId>com.project.test</groupId>
    <artifactId>account-aggregator</artifactId>
    <version>1.0.0-SNAPSHOT</version>
    <!-- 打包方式必须为 pom -->
    <packaing>pom</packaing>
    <name>Account Aggregator</name>
    <!-- 声明 module 实现模块聚合，module 值为当前 POM 的相对目录 -->
    <modules>
      	<module>../account-email</module>
      	<module>../account-persist</module>
    </modules>
</project>
```

##### 继承

###### 父 pom

可以创建父 POM，在父 POM 中声明一些配置供子 POM 继承，父模块本身不包含除 POM 之外的项目文件。

*parent pom*

```xml
<project>
    <groupId>com.project.test</groupId>
    <artifactId>project-parent</artifactId>
    <version>1.0.0-SNAPSHOT</version>
    <!-- 打包类型必须为 POM -->
    <packaging>pom</packaging>
    <name>Account Parent</name>
</project>
```

*sub pom*

```xml
<project>
    <parent>
      <!-- groupId、artifactId、version 必须存在，指定父模块的坐标 -->
      <groupId>com.project.test</groupId>
      <artifactId>account-parent</artifactId>
      <version>1.0.0</version>
      <!-- 指定父模块相对路径 -->
      <relativePath>../account-parent/pom.xml</relativePath> 
    </parent>
</project>
```

当项目构建时，maven 会首先根据 relativePath 检查父 POM，如果找不到，再从本地仓库查找，relativePath 默认值为 ../pom.xml，即 maven 默认父 POM 在上一层目录下。

多模块下可以将父模块加入到聚合模块中，也可以指定父模块为聚合模块

###### 可继承元素

|          元素          |                 说明                 |
| :--------------------: | :----------------------------------: |
|        groupId         |               项目组ID               |
|        version         |               项目版本               |
|      description       |               项目描述               |
|      organization      |             项目组织信息             |
|     inceptionYear      |             项目创始年份             |
|          url           |           项目的 URL 地址            |
|       developers       |           项目的开发者信息           |
|      contributors      |           项目的贡献者信息           |
| distributionManagement |            项目的部署配置            |
|    issueManagement     |        项目的缺陷跟踪系统信息        |
|      ciManagement      |        项目的持续集成系统信息        |
|          scm           |        项目的版本控制系统信息        |
|      mailingLists      |          项目的邮件列表信息          |
|       properties       |         自定义的 Maven 属性          |
|      dependencies      |            项目的依赖配置            |
|  dependencyManagement  |          项目的依赖管理配置          |
|      repositories      |            项目的仓库配置            |
|         build          |               构建配置               |
|       reporting        | 项目的报告输出目录配置，报告插件配置 |

###### 依赖继承管理

maven 提供的 dependencyManagement 元素既能让子模块继承到父模块的依赖配置，又能保证子模块依赖使用的灵活性

使用 dependencyManagement 声明的依赖不会引入实际的依赖（父模块和子模块都不会引入），它能够约束 dependencies 下依赖使用。如果子模块不声明依赖的使用，即使该依赖已经在父 POM 的 dependencyManagement 中声明了，也不会产生任何实际效果

###### 依赖范围导入

该范围的依赖只在 dependencyManagement 元素下才有效果，使用该范围的依赖通常指向一个 POM，作用是将目标 POM 中的 dependencyManagement 配置导入并合并到当前 POM 的 dependencyManagement 元素中。

*import依赖范围*

```xml
<dependencyManagement>
    <dependencies>
        <dependency>
            <groupId>com.project.test</groupId>
            <artifactId>account-parent</artifactId>
            <version>1.0.0-SNAPSHOT</version>
            <type>pom</type>
            <scope>import</scope>
        </dependency>
    </dependencies>
</dependencyManagement>
```

import 范围依赖一般指向打包类型为 pom 的模块，如果有多个项目，它们使用的依赖版本都是一致的，则可以定义一个使用 dependencyManagement 专门管理依赖的 POM，然后在各个项目中导入这些依赖管理配置

##### 反应堆

###### 构建顺序

对于单模块的项目，反应堆就是该模块本身。多模块的项目中，反应堆（Reactor）是指所有模块组成的一个构建结构。对于多模块项目，反应堆就包含了各模块之间继承和依赖的关系，从而能够自动计算出合理的模块构建顺序。

实际的构建顺序是：maven 按序读取 POM，如果该 POM 没有依赖模块，那么就构建该模块，否则就先构建其依赖模块，如果该依赖还依赖于其他模块，则进一步先构建依赖的依赖。

模块间的依赖关系会将反应堆构成一个有向非循环图，各个模块是该图的节点，依赖关系构成了有向边。这个图不允许出现循环，当出现模块 A 依赖于 B，而 B 又依赖于 A 的情况时，maven 会报错

###### 裁剪反应堆

有时仅需要构建完整反应堆中的某几个模块，此时需要裁剪反应堆，跳过无须构建的模块，maven 提供了：

* -am，--also-make

  同时构建所列模块的依赖模块

* -amd，-also-make-dependents

  同时构建依赖于所列模块的模块

* -pl，--projects {arg}

  构建指定的模块，模块间用逗号分隔

  ```shell
  mvn clean install -pl account-email,account-persist
  ```

* -rf，-resume-from {arg}

  在完整的反应堆构建顺序基础上指定从那个模块开始构建

#### 配置信息

##### Settings.xml

*settings 元素*

|              元素              |              含义               |
| :----------------------------: | :-----------------------------: |
|           <settings>           |           文档根元素            |
|       <localRepository>        |            本地仓库             |
|       <interactiveMode>        | maven 是否与用户交互，默认 true |
|           <offline>            |      离线模式，默认 false       |
|  <pluginGroups><pluginGroup>   |             插件组              |
|       <servers><server>        |    下载与部署仓库的认证信息     |
|       <mirrors><mirror>        |            仓库镜像             |
|        <proxies><proxy>        |              代理               |
|      <profiles><proflie>       |         Setting Profile         |
| <activeProfile><activeProfile> |          激活 Profile           |

##### pom.xml 

*pom 元素*

|                      元素                      |                 含义                 |
| :--------------------------------------------: | :----------------------------------: |
|                   <project>                    |                根元素                |
|                    <parent>                    |               声明继承               |
|                   <modules>                    |               声明聚合               |
|                   <groupId>                    |               坐标组织               |
|                  <artifactId>                  |               坐标产品               |
|                   <version>                    |               坐标版本               |
|                  <packaging>                   |            打包，默认 jar            |
|                     <name>                     |                 名称                 |
|                 <description>                  |                 描述                 |
|                 <organization>                 |               所属组织               |
|              <licenses><license>               |                许可证                |
|          <mailingLists><mailingList>           |               邮件列表               |
|            <developers><developer>             |                开发者                |
|          <contributors><contributor>           |                贡献者                |
|               <issueManagement>                |               问题追踪               |
|                 <ciManagement>                 |               持续集成               |
|                     <scm>                      |               版本控制               |
|             <prerequisites><maven>             |    要求 maven 最低版本，默认 2.0     |
|            <build><sourceDirectory>            |              主源码目录              |
|         <build><scriptSourceDirectory>         |             脚本源码目录             |
|          <build><testSourceDirectory>          |             测试源码目录             |
|            <build><outputDirectory>            |              主源码目录              |
|          <build><testOutputDirectory>          |           测试源码输出目录           |
|          <build><resources><resource>          |              主资源目录              |
|        <build><resource><testResource>         |             测试资源目录             |
|               <build><finalName>               |           输出主构件的名称           |
|               <build><directory>               |               输出目录               |
|            <build><filters><filter>            | 通过 properties 文件定义资源过滤属性 |
|         <build><extensions><extension>         |          扩展 maven 的核心           |
|           <build><pluginManagement>            |               插件管理               |
|            <build><plugins><plugin>            |                 插件                 |
|              <profiles><profile>               |             POM Profile              |
|      <distributionManagement><repository>      |           发布版本部署仓库           |
| <distributetionManagement><snapshotRepository> |           快照版本部署仓库           |
|         <distributionManagement><site>         |               站点部署               |
|           <repositories><repository>           |                 仓库                 |
|     <pluginRepositories><pluginRepository>     |               插件仓库               |
|           <dependencies><dependency>           |                 依赖                 |
|             <dependencyManagement>             |               依赖管理               |
|                  <properties>                  |              maven 属性              |
|              <reporting><plugins>              |               报告插件               |

### gradle

#### 构建配置

可以在命令行或在项目/用户运行目录下创建 *gradle.properties* 文件，启动时会默认读取这个文件，加载其参数优先级：命令行参数 > 用户 *gradle.properties* > 项目 *gradle.properties*

##### 选项

###### 构建选项

|               命令行               |        gradle.properties        |                         用途                         |         值          |
| :--------------------------------: | :-----------------------------: | :--------------------------------------------------: | :-----------------: |
|      `--deamon`/`--no-daemon`      |       `org.gradle.daemon`       |               构建是否使用 daemon 进程               | boolean，默认 true  |
|                                    | `org.gradle.daemon.idletimeout` |                 空闲 daemon 存活时间                 |       3 小时        |
| `--build-cache`/`--no-build-cache` |      `org.gradle.caching`       |                   构建是否复用缓存                   | boolean，默认 false |
|                                    |   `org.gradle.caching.debug`    |                caching task 的 debug                 | boolean，默认 false |
|           `--no-rebuild`           |                                 | 不编译依赖模块，如果依赖项目已编译且未修改，可以跳过 |                     |
|            `--parallel`            |      `org.gradle.parallel`      |            多项目并行构建，单项目不起作用            | boolean，默认 false |

###### 输入选项

|      命令行参数       |                             作用                             |
| :-------------------: | :----------------------------------------------------------: |
| `-P, --project-prop`  | 设置编译脚本中 project 属性，通过 `project.hasProjperty("xxx")` 获取 |
|  `-D, --system-prop`  |                    设置编译脚本 JVM 属性                     |
|    `--init-script`    | 指定初始化脚本，会按字母顺序执行指定脚本和用户运行目录下 init.d 目录下 *.gralde/*.kts 脚本 |
|   `--settings-file`   |    指定 settings 文件的位置，默认从当前目录开始向上级查找    |
|    `--build-file`     | 指定编译脚本位置，默认当前目录下，build.gradle/build.gradle.kts |
|    `--project-dir`    |                 指定项目根目录，默认当前目录                 |
| `--project-cache-dir` | 项目缓存目录（增量编译缓存目录），默认当前目录下 .gradle 文件夹 |

###### 输出日志选项

|    命令行选项    |     gradle.properties      |     作用     |                         值                          |
| :--------------: | :------------------------: | :----------: | :-------------------------------------------------: |
|   `--console`    |    `org.gradle.console`    | 输出日志格式 |       `auto/plain/rich/verbose`，默认 `auto`        |
|                  | `org.gradle.logging.level` |   日志级别   | `quiet/debug/info/warn/lifecycle`，默认 `lifecycle` |
| `--warning-mode` | `org.gradle.warning.mode`  | 设置警告级别 |         `none/summary/all`，默认 `summary`          |

##### 构建

###### 增量编译及缓存

增量编译与缓存目的一致，区别在于作用范围及缓存内容，同时存在时，增量编译优先

*   增量编译

    Incremental Build/Up-to-date checks。在同一个项目中，同一个 task 除非有必要，否则不会被执行多次，跳过的 task 会显示 UP-TO-DATE，增量编译（默认开启）会在项目根目录下 `.gradle/[version]` 下保存缓存信息，值缓存上一次构建 task

*   缓存

    无论是否在同一个项目（甚至是不同的机器），只要 task 的输入没变，就可以复用缓存的结果而不变真正执行 task。默认关闭

##### 构建脚本

在目录下执行 gradle 命令时，会从当前目录下查找 `settings.gradle(.kts)`（同时构建多个模块时，管理模块之间的依赖关系，构建顺序） 和 `build.gradle(.kts)` （描述构建行为）两个文件

###### 任务

Task，是 Gradle 的执行最小单元，任务之间的依赖关系组成了一个有向无环图，一次构建中每个 task 只会被执行一次。

内置 tasks 对象用于创建和操作任务

*   通过 name 注册 task

    ```kotlin
    tasks.create("foo") {
        // 配置
        println("configure phase...")
        doLast {
            // 执行
            println("execution phase...")
        }
    }
    // 通过 name 获取 task
    tasks["foo"].name
    tasks.getByName("foo").name
    tasks.getByName<Copy>("copy").destinationDir
    // 获取其他 project task
    tasks.getByPath(":projectA:hello").path
    ```

*   通过 kotlin delegate properties 创建任务

    ```kotlin
    val bar by tasks.creating {
    	// 配置阶段
        println("configure phase...")
        doLast {
            // 执行阶段
            println("execution phase...")
        }
    }
    // 通过 kotlin delegate properties 获取
    val foo by task.getting
    val copy by tasks.getting(Copy::class)
    ```

任务间依赖决定了执行顺序以及是否可以并发，声明依赖关系

*   使用 task 名称

    ```kotlin
    tasks.create("foo") {
        doLast {
            println("foo")
        }
    }
    tasks.create("bar") {
        dependsOn("foo") // 使用 task 名称声明依赖关系
        doLast {
            println("bar")
        }
    }
    ```

*   使用 task 对象依赖

    ```kotlin
    val taskFoo by tasks.creating {
        doLast {
            println("foo")
        }
    }
    val taskBar by tasks.creating {
        doLast {
            println("bar")
        }
    }
    taskBar { dependsOn(taskFoo) } // 使用 task 对象声明依赖关系
    ```

    

###### 阶段

Gradle 在执行脚本时，会把脚本的内容分为多个阶段执行，脚本执行会执行：初始化-配置-执行

#### 执行流程

##### 运行模式

###### daemon

gradle 运行后是一个基于 JVM 的普通进程。在命令行执行任意 gradle 命令后，会创建一个 JVM 进程，用于解析命令行参数，这个 Gradle 进程即  client VM，默认 64MB 堆内存，client VM 会创建

独立的 daemon 进程来执行真正的构建任务. 这个 Gradle 进程叫做 *build VM*  默认最大 512MB 堆内存。daemon 进程在编译完当前构建后不会立即退出, 而是以守护进程的方式在后台休眠, 一旦有新的构建任务, 则直接复用当前空闲的 daemon 进程. 这样可以避免进程启动, 初始化 JVM 并加载诸多 jar 的过程, 以此提高启动速度。守护进程是版本相关的, 如果有多个版本的 gradle 守护进程同时在运行, 那么新的编译任务只会选择与 client 进程相同版本的守护进程进行编译

##### 执行流程

Gradle 执行分为：Initialization -> Configuration -> Execution

*   Initialization

    初始化构建，分为两个子过程，执行 Init Script（读取全局脚本，初始化全局通用属性）和 Setting Script（初始化一次构建参与的所有模块）

*   Configuration

    Init 完成后，执行项目所有模块的 Build Script（build.gradle.kts）

#### 快速开始

##### 使用

###### 声明文件

类似于 Maven 的 pom.xml 文件，每个 Gradle 项目都需要一个对应的 build.gradle 文件，该文件定义一些任务（task）来完成构建工作，每个任务是可配置的，任务之间也可以依赖。

###### 约定配置

gradle 提供了 maven 的约定优于配置方式，通过 gradle 的 java plugin 实现，gradle 推荐这种方式

```
src/main/java
src/main/resources
src/test/java
src/test/resources
```

使用 groovy 自定义项目布局

```groovy
sourceSets {
	main {
		java {
			srcDir 'src/java'
		}
		resources {
			srcDir 'src/resources'
		}
	}
}
```

##### 命令行使用

###### 常用命令

* gradle -q tasks

  列出项目中所有可用的 task，gradle 提供了任务组的概念，每个构建脚本都会默认暴露 help tasks 任务组，如果某个 task 不属于一个任务组，那么它就会显示在 other tasks 中。

  要获得关于 task 的更多信息，使用 --all 选项 gradle -q tasks --all，--all 选项是决定 task 执行顺序的好办法。

* gradle <taskname>

  任务执行

* gradle groupTherapy -x yaygradle0

  排除一个任务，gradle 排除 yaygradle0 任务和它依赖的任务 startSession

###### 日志选项

* -i,--info

  默认不会输出很多信息，可以使用该选项改变日志级别为 info

* -s,--stacktrace

  如果构建在运行中出现错误，该选项在有异常抛出时会打印出简短的堆栈跟踪信息

* -q,--quiet

  减少构建出错时打印出来的错误日志信息

##### 守护进程

在命令行中启动 gradle 守护进程：在运行 gradle 命令时加上 --daemon 选项。后续触发的 gradle 命令都会重用守护进程。守护进程只会被创建一次，会在 3 小时空闲时间之后自动过期。执行构建时不使用守护进程：--no-daemon 。手动停止守护进程 gradle --stop。

#### 构建项目

##### 指定 Java 插件

告诉 gradle 使用 Java 插件

```
apply plugin 'java'
```

Java 插件引入的约定之一就是源代码的位置。在默认情况下，插件会到 src/main/java 目录下查找。Java 插件提供的一个任务 build。这个 build 任务会以正确的顺序编译源码，运行测试，组装 JAR 文件。

gradle build，gradle properties 显示可配置标准和插件属性的列表及默认值。

##### 定制项目

* 修改项目和插件属性

  ```groovy
  // 项目版本
  version = 0.1
  // 设置 Java 版本编译兼容
  sourceCompatibility = 11
  // 将 main-class 头添加到 JAR 文件
  jar {
  	manifest {
  		attributes 'Main-Class': 'com.manning.gia.todo.ToDoApp'
  	}
  }
  ```

* 定义仓库

  ```groovy
  repositories {
  	mavenCentral();
  }
  ```

* 定义依赖

  ```groovy
  dependencies {
  	compile groups: 'org.apache.commons', name: 'commons-lang3', version: '3.1'
  }
  ```

  在 Gradle 中，依赖是由 configuration 分组的管理的：`compile`：编译时依赖；`providedCompile`：编译时需要，但由运行时环境提供；`runtime`：编译时不需要，运行时需要

##### Web

gradle 提供了开箱即用的 war 插件，用来组装 WAR 文件和将 web 应用部署到本地 servlet 容器中。

```groovy
apply plugin: 'war'
```

war 应用默认约定的源代码目录是  src/main/webapp，war 插件暴露了 webAppDirName 约定属性，默认值是 src/main/webapp，通过触发 from 方法就可以有选择地将需要的目录添加到 WAR 文件中。

```json
// 修改 Web 应用源代码目录
webAppDirName = 'webfiles'
// 将 css，jsp 目录添加到 WAR 文件的根目录下
war {
	from 'static'
}
```

##### Gradle 包装器

能够让机器在没有安装 Gradle 运行时的情况下运行 gradle 构建，也让构建脚本运行在一个指定的 gradle 版本上，通过自动从中心仓库下 Gradle 运行时，解压和使用来实现。最终是创造一个独立于系统、系统配置和 Gradle 版本的可重复构建，使用包装器被仍为是最佳实践，对每一个 Gradle 项目都是必须的。

#### 构建脚本

##### 构建块

每个 Gradle 构建都包含三个基本构建块：project、task、property。每个构建包含至少一个 project，一个或多个 task。project 和 task 暴露的属性可以用来控制构建。再多项目构建中一个 project 可以依赖于其他的 project，task 可以形成一个依赖关系图来确保它们的执行顺序。

一个项目代表一个正在构建的组件，每个 Gradle 构建脚本至少定义一个项目。当构建进程启动后，Gradle 基于 build.gradle 中的配置实例化 org.gradle.api.Project 类，并且能够通过 project 变量使其隐式可用。一个 project 可用创建新的 task，添加依赖关系和配置，并应用插件和其他构建脚本，它的许多属性，可通过 getter 和 setter 方法访问



##### 扩展属性

Gradle 的很多领域模型类提供了特别的属性支持。在内部，这个属性以键值对的形式存储。为了添加属性，需要使用 ext 命名空间。额外的属性也可以通过属性文件来提供

```groovy
project.ext.myProp = 'myValue'
ext {
    someOtherProp = 123
}
println project.someOtherProp
```

##### Gradle 属性

Gradle 属性可以通过在 gradle.properties 文件中声明直接添加到项目中，这个文件位于 <USER_HOME>/.gradle 目录或项目的根目录下。这些属性可以通过项目实例访问。Gradle 提供了很多其他方式为构建提供属性

* 项目属性通过 -P 命令行选项提供
* 系统属性通过 -D 命令行选项提供
* 环境属性按照 ORG_GRADLE_PROJECT_propertyName=someValue 提供

##### 使用 task

默认情况下，每个新创建的 task 都是 org.gradle.api.DefaultTask 类型，标准的 org.gradle.api.Task 实现。DefaultTask 里的所有属性都是 private 的，它们只能通过 getter 和 setter 方法来访问。task 接口提供了两个相关的方法来声明 task 动作：doFirst(Closure) 和 doLast(Closure) 当 task 被执行时，动作逻辑被定义为闭包参数被依次执行。group 属性用来定义 task 的逻辑分组

```groovy
task printVersion {
    group = 'versioning'
    description = 'Prints project version'
    doLast {
        Logger.quiet "Version: $version"
    }
}
```

dependsOn 方法运行声明依赖一个或多个 task，Gradle 并不能保证 task 依赖的执行顺序。dependsOn 方法只定义了所依赖的 task 需要先执行，在 Gradle 中，执行顺序是由 task 的输入/输出规范自动确定的

```groovy
task third {
    println "third"
}
third.dependsOn('printVersion')
```

Gradle 通过比较两个构建 task 的 inputs 和 outputs 来决定 task 是否是最新的。自从最后一个 task 执行以来，如果 inputs 和 outputs 没有发生变化，则认为 task 是最新的，只有当 inputs 和 outputs 不同时，task 才运行；否则将跳过


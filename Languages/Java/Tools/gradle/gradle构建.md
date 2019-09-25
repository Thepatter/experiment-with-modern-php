## gradle 构建项目

### 构建项目

#### 指定 Java 插件

告诉 gradle 使用 Java 插件

```
apply plugin 'java'
```

Java 插件引入的约定之一就是源代码的位置。在默认情况下，插件会到 `src/main/java` 目录下查找。Java 插件提供的一个任务 build。这个 build 任务会以正确的顺序编译源码，运行测试，组装 JAR 文件。`gradle build`，`gradle properties` 显示可配置标准和插件属性的列表及默认值。

#### 定制项目

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

#### Web

gradle 提供了开箱即用的 war 插件，用来组装 WAR 文件和将 web 应用部署到本地 servlet 容器中。

```groovy
apply plugin: 'war'
```

war 应用默认约定的源代码目录是  `src/main/webapp`，war 插件暴露了 `webAppDirName` 约定属性，默认值是 `src/main/webapp`，通过触发 from 方法就可以有选择地将需要的目录添加到 WAR 文件中。

```gr
// 修改 Web 应用源代码目录
webAppDirName = 'webfiles'
// 将 css，jsp 目录添加到 WAR 文件的根目录下
war {
	from 'static'
}
```

#### Gradle 包装器

能够让机器在没有安装 Gradle 运行时的情况下运行 gradle 构建，也让构建脚本运行在一个指定的 gradle 版本上，通过自动从中心仓库下 Gradle 运行时，解压和使用来实现。最终是创造一个独立于系统、系统配置和 Gradle 版本的可重复构建，使用包装器被仍为是最佳实践，对每一个 Gradle 项目都是必须的。

### 构建脚本

#### 构建块

每个 Gradle 构建都包含三个基本构建块：project、task、property。每个构建包含至少一个 project，一个或多个 task。project 和 task 暴露的属性可以用来控制构建。再多项目构建中一个 project 可以依赖于其他的 project，task 可以形成一个依赖关系图来确保它们的执行顺序。

一个项目代表一个正在构建的组件，每个 Gradle 构建脚本至少定义一个项目。当构建进程启动后，Gradle 基于 build.gradle 中的配置实例化 org.gradle.api.Project 类，并且能够通过 project 变量使其隐式可用。一个 project 可用创建新的 task，添加依赖关系和配置，并应用插件和其他构建脚本，它的许多属性，可通过 getter 和 setter 方法访问

##### 任务

任务动作（task action）：定义了一个当任务执行时最小的工作单元。任务依赖（task dependency）：task 可以定义依赖于其他 task、动作序列和执行条件。每个 project 和 task 实例都提供了可通过 getter 和 setter 方法访问的属性。一个属性可能是一个任务的描述或项目的版本。

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

Gradle 属性可以通过在 `gradle.properties` 文件中声明直接添加到项目中，这个文件位于 `<USER_HOME>/.gradle` 目录或项目的根目录下。这些属性可以通过项目实例访问。Gradle 提供了很多其他方式为构建提供属性

* 项目属性通过 -P 命令行选项提供
* 系统属性通过 -D 命令行选项提供
* 环境属性按照 `ORG_GRADLE_PROJECT_propertyName=someValue` 提供

#### 使用 task

默认情况下，每个新创建的 task 都是 `org.gradle.api.DefaultTask` 类型，标准的 `org.gradle.api.Task` 实现。DefaultTask 里的所有属性都是 private 的，它们只能通过 getter 和 setter 方法来访问。task 接口提供了两个相关的方法来声明 task 动作：`doFirst(Closure)` 和 `doLast(Closure)` 当 task 被执行时，动作逻辑被定义为闭包参数被依次执行。group 属性用来定义 task 的逻辑分组

```groovy
task printVersion {
    group = 'versioning'
    description = 'Prints project version'
    doLast {
        Logger.quiet "Version: $version"
    }
}
```

`dependsOn` 方法运行声明依赖一个或多个 task，Gradle 并不能保证 task 依赖的执行顺序。dependsOn 方法只定义了所依赖的 task 需要先执行，在 Gradle 中，执行顺序是由 task 的输入/输出规范自动确定的

```groovy
task third {
    println "third"
}
third.dependsOn('printVersion')
```

Gradle 通过比较两个构建 task 的 inputs 和 outputs 来决定 task 是否是最新的。自从最后一个 task 执行以来，如果 inputs 和 outputs 没有发生变化，则认为 task 是最新的，只有当 inputs 和 outputs 不同时，task 才运行；否则将跳过


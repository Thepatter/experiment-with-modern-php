## gradle 构建项目

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


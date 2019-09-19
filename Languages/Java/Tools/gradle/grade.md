## gradle

### 概述

类似于 Maven 的 `pom.xml` 文件，每个 Gradle 项目都需要一个对应的 `build.gradle` 文件，该文件定义一些任务（task）来完成构建工作，每个任务是可配置的，任务之间也可以依赖。

#### 约定优于配置

gradle 提供了 maven 的约定优于配置方式，通过 gradle 的 java plugin 实现，gradle 推荐这种方式

```
src/main/java
src/main/resources
src/test/java
src/test/resources
```

区别在于，使用 groovy 自定义项目布局更加方便

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

#### 使用命令行

* 显示脚本任务

  `gradle -q tasks`

* 任务执行

  `gradle <taskname>`

* 排除一个任务

  `gradle groupTherapy -x yaygradle0`

  `gradle` 排除 `yaygradle0` 任务和它依赖的任务 `startSession`

##### 命令行选项

* `-i`

  默认不会输出很多信息，可以使用 -i 选项改变日志级别为 info

* `-s`

  如果运行时错误发生打印堆栈信息

* `-q`

  只打印错误信息

* `-?-h,--help`

* `-b,--build-file:Gradle`

  默认执行 `build.gradle` 脚本，如果执行其他脚本可以使用这个命令

* `--offline`

  在离线
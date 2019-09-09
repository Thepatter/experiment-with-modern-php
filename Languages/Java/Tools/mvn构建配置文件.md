### maven 构建配置文件

构建配置文件是一系列的配置项的值，用来设置或者覆盖 maven 构建默认值。使用构建配置文件，可以为不同的环境，定制构建方式。配置文件在 `pom.xml` 文件中使用 `activeProfiles` 或者 `profiles` 元素指定，并且可以通过各种方式触发。配置文件在构建时修改 `POM`，并且用来给参数设定不同的目标环境。

#### 构建配置文件的类型

* 项目级

  定义在项目的 `POM` 文件  `pom.xml` 中

* 用户级

  定义在 Maven 的设置 xml 文件中（`%USER_HOME%/.m2/settings.xml`)

* 全局

  定义在 Maven 全局的设置 xml 文件中（`%M2_HOME%/conf/settings.xml`）

  


### 使用 maven 构建项目

#### 基础 pom 文件

*pom.xml*

```xml
<?xml version="1.0" encoding="UTF-8"?>
<project xmlns="http://maven.apache.org/POM/4.0.0"
         xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:schemaLocation="http://maven.apache.org/POM/4.0.0 http://maven.apache.org/xsd/maven-4.0.0.xsd">
    <modelVersion>4.0.0</modelVersion>

    <properties>
        <project.build.sourceEncoding>UTF-8</project.build.sourceEncoding>
        <maven.compiler.encoding>UTF-8</maven.compiler.encoding>
        <java.version>11</java.version>
        <maven.compiler.source>11</maven.compiler.source>
        <maven.compiler.target>11</maven.compiler.target>
    </properties>

    <groupId>chaoyi</groupId>
    <artifactId>game</artifactId>
    <version>1.0-SNAPSHOT</version>
    <name>this is test project</name>

</project>
```

配置 `maven-compiler-plugin` 支持 Java 版本

```xml
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
    </plugins>
</build>
```

等价于 `properties` 属性对中 `<maven.compiler.source>` 元素配置

#### 添加依赖

```xml
<dependencies>
    <dependency>
		<groupId>junit</groupId>
    	<artifactId>junit</artifactId>
    	<version>4.7</version>
    	<scope>test</scope>
	</dependency>
</dependencies>
```

* scope

  指定依赖范围，若依赖范围为 test 则表示该依赖只对测试有效。即只能在测试环境导入 Junit，但无法才主代码中导入，如果不指定 scope 默认值为 compile，该依赖对主代码和测试代码都有效


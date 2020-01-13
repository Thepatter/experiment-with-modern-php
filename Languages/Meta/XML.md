### XML

#### 概述

即可扩展标记语言，是一种可以用来创建自定义标记的标记语言。XML 基于 SGML（Standard Generalized Markup Language）标准通用标记语言。

XML 是可扩展的，可以创建自定义元素以满足需要。是结构化的，每个 XML 文档都基于特定的结构。如果一个文档没有适当的结构，那么就不能认为它是 XML。

#### DTD 文档类型定义

DTD（Document Type Definition）是一套定义 XML 标记如何使用的规则，定义了元素、元素的属性和值，以及元素的包含关系，还可以用于定义实体

*E-mail的DTD文件*

![](./Images/E-mail的DTD文件.jpeg)

对应的 XML 文档具备如下特征：

* `<Mail>` 元素包含一个 `<From>`、一个 `<To>`、一个可选的 `<Cc>`、一个可选的 `<Date>`、一个 `<Subject>`、一个 `<Body>`
* `<From>`、`<To>`、`<Cc>`、`<Date>`、`<Subject>` 元素只包含文本信息
* `<Body>` 元素可以含有文本和零个或多个 `<P>` 和 `<Br>` 元素
* `<P>` 元素可以包含文本和零个或多个 `<Br>` 元素
* `<P>` 元素有一个 `align` 属性，取值范围是 `left`、`justify`、`right`，默认值 `left`
* `<Br>` 元素的内容为空

在 DTD 文件中，可以使用一些特殊符号来修饰元素：

* 无符号

  该子元素在父元素内必须存在且只能存在一次

* `+`

  该子元素在父元素内必须存在，可以存在一次或多次

* `*`

  该子元素在父元素内可以不存在，或者存在一次或多次，它是比较常用的符号

* `?`

  该子元素在父元素内可以不存在，或者只存在一次，它是比较常用的符号

XML 解析器将使用这个 DTD 文档来解析 XML 文档。XML 文件开头的 `<! DOCTYPE>` 元素提供了这一功能

```xml
<?xml version='1.0' standalone='no'>
<!DOCTYPE Mail system "http://mymailsystem.com/DTDS/mail.dtd">
<Mail>
</Mail>
```

#### Schema

W3C 支持一种基于 XML 的 DTD 代替者，基于 XML编写，支持数据类型和命名空间，即 XML Schema，可描述 XML 文档的结构，也可作为 XSD（XML Schema Definition）来引用。

```xml
<?xml version='1.0'>
// xs 根元素
<xs:schema>
</xs:schema>
```

##### XML 中引用 Schema

```xml
<?xml version="1.0"?>
<!-- 默认命名空间的声明，告知 schema 验证器，此 XML 文档中使用的所有元素都被声明于该命名空间下 -->
<note xmlns="http://www.runoob.com"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" <!-- 可用的 XML Schema 实例命名空间 -->
xsi:schemaLocation="http://www.runoob.com note.xsd"> <!-- XML实例定位，包含命名空间和xsd位置 -->

<to>Tove</to>
<from>Jani</from>
<heading>Reminder</heading>
<body>Don't forget me this weekend!</body>
</note>
```

#### XML 文档

XML 文档分为两类：

* 简化格式的 XML 文档：其特征为没有相应的 DTD 文档
* 有效的 XML 文档：其特征为必须有相应的 DTD 文档

##### 简化格式的 XML 文档

简化格式的 XML 文档必须遵循：

* 至少有一个元素
* 遵循 XML 规范
* 根元素应该不被其他元素所包含
* 适当的元素嵌套
* 除了保留实体外，所有的实体都要声明

```xml
<?xml version='1.0' standalone='yes'>
```

standalone 属性取值为 yes，表示该 XML 文档是独立的，它不需要特定的 DTD 文件来验证其中的 XML 标记。默认为 no。该属性不是必须的

##### 有效的 XML 文档

有效 XML 文档指的是那些拥有一个DTD 参考文件的 XML 文档。一个有效 XML 文档必须首先是简化格式的 XML文档。而这个文档的 DTD 文件则可以保证 XML 执行程序能正常运行，以及 XML 文档能在支持 XML 的浏览器中正确显示。

##### XML 命名空间

XML 命名空间（xmlns 属性）提供了一种避免元素名冲突的方法（还可以使用前缀解决名字冲突）


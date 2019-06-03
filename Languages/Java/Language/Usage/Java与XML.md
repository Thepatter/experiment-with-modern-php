## Java 与 XML

### XML 结构

#### XML 规范

* XML 是大小写敏感的。

* 在 XML 中结束标签不能省略

* 在 XML 中，只有单个标签而没有相对应的结束标签的元素必须以 `/` 结尾

  ```xml
  <img src="coffeecup.png"/>
  ```

* 在 XML 中，属性值必须用引号括起来

* 在 XML 中，所有属性必须有属性值

#### 结构

* XML 文档应当以一个文档头开始

  ```xml
  <?xml version="1.0" encoding="UTF-8"?>
  ```

* 文档头之后通常是文档类型定义，文档类型定义是确保文档正确的一个重要机制。但它不是必须的。

* XML 文档的正文包含根元素，根元素包含其他元素。

* 元素可以有子元素和文本或者两者都有。设计 XML 文档结构时，最好让元素要么包含子元素，要么包含文本。

* XML 元素可以包含属性，良好实践是：属性只应该用来修改值的解释，而不是用来指定值。

  ```xml
  <size unit="pt">36</size>
  ```

#### XML 标记

* 字符引用的形式是 `&#` 十进制值；或 `&#x` 十六进制值

* 实体引用的形式是 `&name`

  `&lt`：小于；`&gt`：大于；`&amp`：`&`；`&quot`：引号；`&apos`：省略号

* `CDATA` 部分用 `<![CDATA[ ]]` 来限定其界限。是字符数据的一种特殊形式。可以使用它们包含那些含有 `<,>,&` 之类字符的字符串，而不必将它们解释为标记

  ```xml
  <![CDATA[< &> are my favorite delimiters]]
  ```

* 注释用 `<!--` 和 `-->`  限定其界限

### 解析 XML 文档

要处理 XML 文档，就要先解析它。解析器是这样一个程序：它读入一个文件。确认这个文件具有正确的格式，然后将其分解成各种元素，是的程序员能够访问这些元素

Java 库提供了两种 XML 解析器

* 文档对象模型（Document Object Model，DOM）解析器这样的树形解析器，将读入的 XML 文档转换成树形结构
* XML 简单 API （Simple API for XML，SAX）解析器这样的流机制解析器，在读入 XML 文档时生成相应的事件

要读入一个 XML 文档，首先需要一个 `DocumentBuilder` 对象，可以从 `DocumentBuilderFactory` 中的得到这个对象。

```java
DocumentBuilderFactory factory = DocumentBuilerFactory.newInstance();
DocumentBuilder builder = factory.newDocumentBuilder();
```

读入文档

```java
// 从文件中读入
File f = Files.get();
Document doc = builder.parse(u);
// 从流中读入
InputStream in = ...;
Document doc = builder.parse(in);
```

如果使用输入流作为输入源，那么对于那些以该文档的位置为相对路径而被引用的文档，解析器将无法定位。

`Document` 对象是 XML 文档的树结构在内存中的表示方式，由实现了 `Node` 接口及其各种子接口的类的对象构成。

*Node接口及其子接口*

![](Language/Images/node接口及其子接口.png)

可以通过调用 `getDocumentElement` 方法来启动对文档内容的分析，它将返回根元素。使用 `getChildNodes` 方法会返回一个类型为 `NodeList` 的集合。`item` 方法将得到指定索引值的项。`getLength` 方法提供了项的总和。

```java
// 只得到子元素，忽略空白字符
for (int i = 0; i < children.getLength(); i++) {
    Node child = children.item(i);
    if (child instanceof Element) {
        Element childElement = (Element) child;
    }
}
```

如果知道 `Text` 节点是唯一的子元素，用 `getFirstChild` 方法而不用再遍历另一个 `NodeList`。然后可以用 `getData` 方法获取存储在 `Text` 节点中的字符串。也可用 `getLastChild` 方法得到最后一项子元素，用 `getNextSibling` 得到下一个兄弟节点。

如果要枚举节点的属性，可以调用 `getAttributes` 方法。返回一个 `NameNodeMap` 对象。其中包喊了描述属性的 `Node` 对象。可以用和遍历 `NodeList` 一样的方式在 `NamedNodeMap` 中遍历各子节点。然后调用 `getNodeName` 和 `getNodeValue` 方法得到属性名和属性值

```java
// 获取属性名和属性值
NameNodeMap attributes = element.getAttributes();
for (int i = 0; i < attributes.getLength(); i++) {
    Node attribute = attributes.item(i);
    String name = attribute.getNodeName();
    String value = attribute.getNodeValue();
}
// 直到属性名，直接获取相应的属性值
String unit = element.getAttributes("unit");
```

### 流机制解析器

`DOM` 解析器会完整读入 `XML` 文档，然后将其转换成一个树形的数据结构。对于大多数应用，`DOM` 都运行的很好。但是如果文档很大，并且处理算法又非常简单，可以在运行时解析节点，而不必看到完整的树形结构，那么 `DOM` 可能就会显得效率低下了。在这种情况下，可以使用流机制解析器：`SAX` 解析器和现代化 `StAX` 解析器。`SAX` 解析器使用的是事件回调，而 `StAX`  解析器提供了遍历解析事件的迭代器，后者更方便

#### 使用 SAX 解析器

`SAX` 解析器在解析 XML 输入数据的各个组成部分时会报告事件，但不会以任何方式存储文档，而是由事件处理器建立相应的数据结构。实际上，DOM 解析器是在 SAX 解析器的基础上构建的，它在接收到解析器事件时构建 DOM 树

在使用 `SAX` 解析器时，需要一个处理器来为各种解析器事件定义事件动作。`ContentHandler` 接口定义了若干个在解析文档时解析器会调用的回调方法。常用的有

* `startElement` 和 `endElement` 在每当遇到起始或终止标签时调用
* `characters` 在每当遇到字符数据时调用
* `startDocument` 和 `endDocument` 分别在文档开始和结束时各调用一次

处理器必须覆盖这些方法，让它们执行在解析文件时我们想要让它们执行的动作。

#### 使用 StAX 解析器

`StAX` 解析器是一种，拉解析器，与安装事件处理器不同，只需使用下面这样的基本循环来迭代所有的事件

```java
InputStream in = url.openStream();
XMLInputFactory = XMLInputFactory.newInstance();
XMLStreamReader parser = factory.createXMLStreamReader(in);
while (parser.hasNext()) {
    int event = parser.next();
   
}
```

### 生成 XML 文档

#### 不带命名空间的文档

要建立一个 DOM 树，可以从一个空的文档开始。通过调用 `DocumentBuilder` 类的 `newDocument` 方法可以得到一个空文档

```java
Document doc = builder.newDocument();
```

`Document` 类的 `createElement` 方法可以构建文档里的元素

```java
Element rootElement = doc.createElement(rootName);
Element childElement = doc.createElement(childName);
```

使用 `createTextNode` 方法构建文本节点

```java
Text textNode = doc.createTextNode(textContents);
```

`appendChild` 添加子节点和根元素

```java
doc.appendChild(rootElement);
rootElement.appendChild(childElement);
childElement.appendChild(textNode);
```

设置元素属性`Element` 类的 `setAttribute` 

```java
rootElement.setAttribute(name, value);
```

#### 带命名空间的文档

将生成器工厂设置为是命名空间感知的，然后再创建生成器

```java
DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
factory.setNamespaceAware(true);
builder = factory.newDocumentBuiler();
```

使用 `createElementNS` 而不是 `createElement` 来创建所有节点

```java
String namespace = "http://www.w3.org/2000/svg";
Element rootElement = doc.createElementNS(namespace, "svg");
```

如果节点具有带命名空间前缀的限定名，那么所有必须的带有 `xmlns` 前缀的属性都会被自动创建。

```java
Element svgElement = doc.createElement(namespace, "svg:svg");
```

当该元素被写入 XML 文件时，会转变为

```xml
<svg:svg xmlns:svg="http://www.w3.org/2000/svg">
```

使用 `Element` 类的 `setAttributeNS` 方法设置属性的名字位于命名空间中

```java
rootElement.setAttributeNS(namespace, qualifiedName, value);
```

#### 输出文档

```java
Transformer t = TransformerFactory.newInstance().newTransformer();
t.setOutputProperty(OutputKeys.DOCTYPE_SYSTEM, systemIdentifier);
t.setOutputProperty(OutputKeys.DOCTYPE_PUBLIC, publicIdentifier);
t.setOutputProperty(OutputKeys.INDENT, "yes");
t.setOutputProperty(OutputKeys.METHOD, "xml");
t.setOutputProperty("{http://xml.apache.org/xslt}indent-amount", "2");
t.transform(new DOMSource(doc), new StreamResult(new FileOutputStream(file)));
```

使用 `LSSerializer` 接口

```java
DOMImplementation impl = doc.getImplementation();
DOMImplementationLS implLS = (DOMImplementationLS) impl.getFeature("LS", "3.0");
LSSerializer ser = implLS.createLSSerializer();
// 将文档转换为字符串
String str = ser.writeToString(doc);
// 直接写入到文件中
LSOutput out = implLS.createLSOutput();
out.setEncoding("UTF-8");
out.setByteStream("Files.newOutputStream(path)");
ser.write(doc, out);
```

#### 使用 StAX 写出 XML 文档

`StAX` API 可以直接将 XML 树写出，这需要从某个 `OutputStream` 中构建一个 `XMLStreamWriter`

```java
XMLOutputFactory factory = XMLOutputFactory.newInstance();
XMLStreamWriter writer = factory.createXMLStreamWriter(out);
// 生成 XML 文件头
writer.writeStartDocument();
writer.writeStartElement(name);
// 添加属性
writer.writeAttribute(name, value);
// 添加新的子节点
writer.writeCharacters(text);
// 关闭元素
writer.writeEndElement();
// 写出没有子节点的元素
writer.writeEmptyElement(name);
// 关闭所有打开的元素
writer.writeEndDocument();
```


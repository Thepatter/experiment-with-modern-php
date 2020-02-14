### XML

#### Dom 解析器

JDK 中包含了从 Apache 解析器导出的 DOM 解析器。要读入一个 XML 文档，首先需要一个 DocumentBuilder 对象，可以从 DocumentBuilderFactory 中得到这个对象

```java
DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
// 文档生成工厂打开验证特性
factory.setValidating(true);
// 不包含看文本节点的空白字符
factory.setIgnoringElementContentWhitespace(true);
DocumentBuilder builder = factory.newDocumentBuilder();
// 从文件中读取某个文档
File f = ...
Document doc = builder.parse(f);
// 使用 URL
URL u = ...
Document doc = builder.parse(u);
// 任意的输入流,对于那些以该文档的位置为相对路径而被引用的文档，解析器将无法定位（如同目录中的DTD）
InputStream in = ...
Document doc = builder.parse(in);
// 获取文档根元素
Element root = doc.getDocumentElement();
// 得到该元素的子元素
/**
 * <font>
 *		<name>Helvetica</name>
 *		<size>36</size>
 * </font>
 */
NodeList children = root.getChildNodes();
for (int i = 0; i < children.getLength(); i++) {
  	// 得到子元素，忽略空白字符
  	Node child = children.item(i);
  	if (child instanceof Element) {
      	Element childElement = (Element) child;
      	/**
      		* 获取 name，size 包含的文本，这些文本本身都包含在 Text 类型的子节点中。知道这些 Text 节点是唯一
      		* 的子元素，就可以使用 getFirstChild 方法而不用再遍历另一个 NodeList。然后可用 getData 方法
      		* 获取存储在 Text 节点中的文本
          */ 
      	Text textNode = (Text) childElement.getFirstChild();
      	String text = textNode.getData().trim();
      	String name;
      	String size;
      	if ("name".equals(childElement.getTagName())) {
          	name = text;
        }
      	if ("size".equals(childElement.getTagName())) {
          	size = Integer.parseInt(text);
        }
      	// 获取节点属性。知道属性名直接获取对应属性值 element.getAttirbute("key")
      	NamedNodeMap attributes = element.getAttirbutes();
      	for (int i = 0; i < attributes.getLength(); i++) {
          	Node attribute = attributes.item(i);
          	String name = attribute.getNodeName(); // 获取属性名
            // 获取属性值
            String value = attribute.getNodeValue(); 
        }
    }
}
```


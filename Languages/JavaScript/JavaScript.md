### JavaScript 

#### 对象

JavaScript 中对象分为：用户自定义对象；内建对象，宿主（浏览器、node）对象

##### 浏览器对象

###### DOM

DOM 将文档转为文档对象，代表着加载到浏览器窗口的当前网页。DOM 把文档表示为一棵树。

* 节点对象
  
  元素节点（nodeType = 1）对应 HTML 标签，标签的名字就是元素的名字（通过 `getElementById` 获取特定 id 属性的对象、`getElementsByTagName` 获取特定标签的对象数组、`getElementsByClassName` 获取特定 class 属性的对象数组）、文本节点（nodeType = 2）对应标签内容（通过 node.nodeValue 操作文本属性值，获取元素文本 `node.childNodes[0].nodeValue`）、属性节点（nodeType = 3）对应标签属性(可以通过 `getAttribute`/`setAttribute` 来获取和设置属性节点值)
  

###### BOM

浏览器对象，对应着浏览器窗口本身。
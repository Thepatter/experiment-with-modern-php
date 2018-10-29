## Iterator 迭代器模式

### Iterator 模式中的登场角色

* `Iterator` 迭代器

  该角色负责定义按顺序逐个遍历元素的接口,定义了 `hasNext` 和 `next` 两个方法, `hasNext` 方法用于判断是否存在下一个元素, `next` 方法则用于获取该元素

* `ConcreteIterator` 具体的迭代器

  该角色负责实现 `Iterator` 角色所定义的接口,该角色中包含了遍历集合所必须的信息.

* `Aggregate` 集合

  该角色负责定义创建 `Iterator` 角色的接口,这个接口是一个方法,会创建出(按顺序访问保存在我内部元素的人)

* `CibcreteAggregate` 具体的集合

  该角色负责实现 `Aggregate` 角色所定义的接口,他会创建出具体的 `Iterator` 角色,即 `ConcreteIterator` 角色.

### Iterator 模式类图

![](C:\Users\work\IdeaProjects\some_book\DesignPatterns\ClassDiagram\Iterator.jpg)

## Adapter 适配器模式

* 类适配器模式(使用继承的适配器)
* 对象适配器模式(使用委托的适配器)

### Adapter 模式中的登场角色

* `Target` 对象: 该角色负责定义所需的方法(使用继承时使用接口,使用委托时使用类)
* `Client` 请求者: 该角色负责使用 `target` 角色所定义的方法进行具体处理
* `Adaptee` 被适配: `Adaptee` 是一个持有既定方法的角色.如果 `Adaptee` 角色中的方法与 `target` 角色的方法相同,就不需要 `Adapter` 角色
* `Adapter` 适配: 使用 `Adaptee` 角色的方法来满足 `target` 角色的需求,在类适配器模式中,`Adapter` 角色通过继承来使用 `Adaptee` 角色.而在对象适配器模式中, `Adapter` 角色通过委托来使用 `Adaptee` 角色

### Adapter 模式类图

* 类适配器模式类图

![](C:\Users\work\IdeaProjects\some_book\DesignPatterns\ClassDiagram\类适配器模式类图.png)

* 对象适配器模式类图

  ![](C:\Users\work\IdeaProjects\some_book\DesignPatterns\ClassDiagram\对象适配器模式的类图.png)

### 使用

`Adapter` 模式会对现有的类进行适配,生成新的类.通过该模式可以很方便的创建我们需要的方法群,并非一定需要现成的代码,只要知道现有类的功能,就可以编写出新的类


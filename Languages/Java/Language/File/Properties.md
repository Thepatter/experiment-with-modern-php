#### Properties

##### Properties

属性映射是一种存储键/值对的数据结构。属性映射通常用来存储配置信息

* 键和值是字符串

* 映射可以很容易地存入文件以及从文件加载

* 有一个二级表保存默认值

实现类为 *Properties*

可以使用 store 方法存储在文件中，由于历史原因，*Properties* 实现了 Map 接口，因此可以使用 Map 接口的 get 和 put 方法。不过会返回 object，最好使用 getPropertey 和 setPropertey 方法，这些方法处理字符串

##### Preferences

使用属性文件缺点：

* 有序操作系统没有主目录的概念，所以很难找到一个统一的配置文件位置
* 关于配置文件的命名没有标准约定，用户安装多个 Java 应用时，就更容易发生命名冲突

*Preferences* 以一种平台无关的方式提供了一个中心存储库。在 Windows 中，*Preferences* 使用注册表来存储信息；在 Linux 中，信息存储在本地文件系统中。存储库实现对使用 *Preferences* 类的程序员是透明的

*Preferences* 存储库有一个树状结构。类似于包名结构。存储库的各个节点分别有一个单独的键/值对表，可以用来存储数值、字符串或字节数组，但不能存储可串性化的对象。可以有多个并行的树，每个程序用户分别有一棵树；还有另外一棵系统树，可以用于存放所有用户的公共信息。*Preference* 使用操作系统的『当前用户』概念来访问适当的用户树。

```java
// 获取用户根节点
Preferences root = Preferences.userRoot();
// 访问节点
Preferences node = root.node("/com/mycompany/myapp");
// 根据类包名获取节点
Preferences node = Preferences.userNodeForPackage(this.getClass());
// 访问键/值表，读取信息时必须指定一个默认值，以防止没有可用的存储库数据
String get(String key, String def);
// 存储
put(String key, String value);
// 导出树或节点，以 xml 格式导出
void exportSubtree(OutputStream out);
void exportNode(OutputStream out);
```


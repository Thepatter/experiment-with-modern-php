java.util.ResourceBundle

```java
// 使用指定的基本名称，默认语言环境获取资源包和调用模块 baseName 资源基本名称，完全限定的类名称，必须能访问
static ResourceBundle getBundle(@NotNull() String baseName);
// 使用指定的基本名称和 Locale 获取默认资源包和调用模块
static ResourceBundle getBundle(String baseName);
// 从资源包或它的父包中查找一个对象
Object getObject(String name);
// 从资源包或它的父包中查找一个对象并把它转型成字符串
String getString(String name);
// 从资源包或它的父包中查找一个对象并把它转型成字符串数组
String[] getStringArray(String name);
// 返回一个枚举对象，枚举出资源包中的所有键，包括父包中的键
Enumeration<String> getKeys();
// 如果要定义自己的资源查找机制，那么这个方法就需要被覆写，用来查找与给定的键相关联的资源的值
Object handleGetObject(String key);
```
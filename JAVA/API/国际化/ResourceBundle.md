## java.util.ResourceBundle

* `static ResourceBundle getBundle(String baseName, Locale loc)`

* `static ResourceBundle getBundle(String baseName)`

  在给定的 `locale` 或默认的 `locale` 下以给定的名字加载资源绑定类和它的父类。如果资源包类位于一个 `Java` 包中，那么类的名字必须包含完整的包名。资源包类必须是 `public` 的，这样 `getBundle` 方法才能访问它们

* `Object getObject(String name)`

  从资源包或它的父包中查找一个对象

* `String getString(String name)`

  从资源包或它的父包中查找一个对象并把它转型成字符串

* `String[] getStringArray(String name)`

  从资源包或它的父包中查找一个对象并把它转型成字符串数组

* `Enumeration<String> getKeys()`

  返回一个枚举对象，枚举出资源包中的所有键，也包括父包中的键

* `Object handleGetObject(String key)`

  如果要定义自己的资源查找机制，那么这个方法就需要被覆写，用来查找与给定的键相关联的资源的值
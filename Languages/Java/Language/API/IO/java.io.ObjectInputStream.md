*java.io.ObjectInputStream*
```java
ObjectInputStream(InputStream in);

// 创建一个 `ObjectInputStream` 用于从指定的 `InputStream` 中读回对象信息

Object readObject();

// 从 `ObjectInputStream` 中读入一个对象。这个方法会读回对象的类，类的签名以及这个类及其超类中所有非静态和非瞬时的域的值。它执行的反序列化允许恢复多个对象引用

```
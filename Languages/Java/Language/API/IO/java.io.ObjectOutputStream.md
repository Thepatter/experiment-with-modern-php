*java.io.ObjectOutputStream*
```java
ObjectOutputStream(OutputStream out);

// 创建一个 `ObjectOutputStream` 使得可以将对象写出到指定的 `OutputStream`

void writeObject(Object obj);

// 写出指定的对象到 `ObjectOutputStream`，这个方法将存储指定对象的类，类的签名以及这个类及其超类中所有非静态和非瞬时的域的值

```
*java.util.Enumeration<E>*
```java
boolean hasMoreElements();

// 如果还有更多的元素可以查看，则返回 true

E nextElement();

// 返回被检测的下一个元素。如果 `hasMoreElements()` 返回 false，则不要调用这个方法

```
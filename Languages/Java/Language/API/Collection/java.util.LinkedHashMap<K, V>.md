*java.util.LinkedHashMap<K, V>*
```java
LinkedHashMap();

LinkedHashMap(int intialCapacity);

LinkedHashMap(int initialCapacity, float loadFactor);

LinkedHashMap(int initialCapacity, float loadFactor, boolean accessOrder);

// 用给定的容量、填充因子和顺序构造一个空的链接散列映射表。`accessOrder` 参数为 `true` 时表示访问顺序，为 `false` 表示插入顺序

protected boolean removeEldestEntry(Map.Entry<K, V> eldest);

// 如果想删除 `eldest` 元素，并同时返回 `true`，就应该覆盖这个方法。`eldest` 参数是预期要删除的条目。这个方法将在条目添加到映射中之后调用。其默认的实现将返回 `false`。即在默认情况下，旧元素没有被删除。

// 可以重新定义这个方法，以便有选择的访问 true。

```
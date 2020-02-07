*java.util.Map<K, V>*
```java
V get(Object key);

// 获取与键对应的值；返回与键对应的对象，如果在映射中没有这个对象则返回 null。键可以为 null

default V getOrDefault(Object key, V defaultValue);

// 获得与键关联的值；返回与键关联的对象，如果未在映射中找到这个键，则返回 `defaultValue`

V put(K key, V value);

// 将键与对应的值关系插入到映射中。如果这个键已经存在，新的对象将取代与这个键对应的旧对象。这个方法将返回键对应的旧值。如果这个键以前没有出现则返回 null，键可以为 null，但值不能为 null

V putAll(Map<? extends k, ? extends V> entries);

// 将给定映射中的所有条目添加到这个映射中

boolean containskey(Object key);

// 如果在映射中已经有这个值，返回 true

default void forEach(BiConsumer<? super k, ? super V> action);

// 对这个映射中的所有键/值对应用这个动作

default V merge(k key, V value, BiFuntion<? super V, ? super V, ? extends V> remappingFunction);

// 如果 `key` 与一个非 `null` 值 v 关联，将函数应用到 `v` 和 `value`，将 `key` 与结果关联，或者如果结果为 null，则删除这个键。否则，将 `key` 与 `value` 关联，返回 `get(key)`

default V compute(K key, BiFunction<? super K, ? super V, ? extends V>remappingFunction);

// 将函数应用到 `key` 和 `get(key)`。将 `key` 与结果关联，或者如果结果为 null，则删除这个键。返回 `get(key)`

default V computeIfPresent(K key, BiFunction<? super K, ? super V,? extends V> remappingFunction);

// 如果 `key` 与一个非 `null` 值 `v` 关联，将函数应用到 `key` 和 `v`，将 `key` 与结果关联，或者如果结果为 null，则删除这个键。返回 `get(key)`

default V computeIfAbsent(K key, Function<? super K, ? extends V>mappingFunction);

// 将函数应用到 `key`，除非 `key` 与一个非 `null` 值关联。将 `key` 与结果关联，或者如果结果为 `null`，则删除这个键。返回 `get(key)`

default void replaceAll(BiFunction<? super K, ? super V, ? extends V> function);

// 在所有映射项上应用函数。将键与非 null 结果关联，对于 null 结果，则将相应的键删除

Set<Map.Entry<K, V>> entrySet();

// 返回 `Map.Entry` 对象（映射中的键、值对）的一个集视图。可以从这个集中删除元素，它们将从映射中删除，但是不能增加任何元素

Set<K> keySet();

// 返回映射中所有的键的一个集视图。可以从这个集中删除元素，键和相关的值将从映射中删除，但是不能增加任何元素

Collection<V> values();

// 返回映射中所有值的一个集合视图。可以从这个集合中删除元素，所删除的值及相应的键将从映射中删除，不过不能增加任何元素

```
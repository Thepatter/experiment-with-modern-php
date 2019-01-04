## `java.util.Map<K, V>`

* `default V merge(k key, V value, BiFuntion<? super V, ? super V, ? extends V> remappingFunction)`

  如果 `key` 与一个非 `null` 值 v 关联，将函数应用到 `v` 和 `value`，将 `key` 与结果关联，或者如果结果为 null，则删除这个键。否则，将 `key` 与 `value` 关联，返回 `get(key)`

* `default V compute(K key, BiFunction<? super K, ? super V, ? extends V>remappingFunction)`

  将函数应用到 `key` 和 `get(key)`。将 `key` 与结果关联，或者如果结果为 null，则删除这个键。返回 `get(key)`

* `default V computeIfPresent(K key, BiFunction<? super K, ? super V,? extends V> remappingFunction)`

  如果 `key` 与一个非 `null` 值 `v` 关联，将函数应用到 `key` 和 `v`，将 `key` 与结果关联，或者如果结果为 null，则删除这个键。返回 `get(key)`

* `default V computeIfAbsent(K key, Function<? super K, ? extends V>mappingFunction)`

  将函数应用到 `key`，除非 `key` 与一个非 `null` 值关联。将 `key` 与结果关联，或者如果结果为 `null`，则删除这个键。返回 `get(key)`

* `Set<Map.Entry<K, V>> entrySet()`

  返回 `Map.Entry` 对象（映射中的键、值对）的一个集视图。可以从这个 集中删除元素，它们将从映射中删除，但是不能增加任何元素

* `Set<K> keySet()`

  返回映射中所有的键的一个集视图。可以从这个集中删除元素，键和相关的值将从映射中删除，但是不能增加任何元素

* `Collection<V> values()`

  返回映射中所有值的一个集合视图。可以从这个集合中删除元素，所删除的值及相应的键将从映射中删除，不过不能增加任何元素
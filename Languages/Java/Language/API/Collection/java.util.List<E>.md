*java.util.List<E>*
```java
ListIterator<E> listIterator();

// 返回一个列表迭代器，以便用来访问列表中的元素

ListIterator<E> listIterator(int index);

// 返回一个列表迭代器，以便用来访问列表中的元素，这个元素是第一次调用 `next` 返回的给定索引的元素

void add(int i, E element);

// 在给定位置添加一个元素

void addAll(int i, Collection<? extends E> elements);

// 将某个集合中的所有元素添加到给定位置

E remove(int i);

// 删除给定位置的元素并返回这个元素

E get(int i);

// 获取给定位置的元素

E set(int i, E element);

// 用新元素取代给定位置的元素，并返回原来那个元素

int indexOf(Object element);

// 返回与指定元素相等的元素在列表中第一次出现的位置，如果没有这样的元素将返回 -1.

int lastIndexOf(Object element);

// 返回与指定元素相等的元素在列表中最后一次出现的位置，如果没有这样的元素将返回 -1

List<E> subList(int firstIncluded, int firstExcluded);

// 返回给定位置范围内的所有元素的列表视图

default void sort(Comparator<? super T> comparator);

// 使用给定比较器对列表排序

default void replaceAll(UnaryOperator<E> op);

// 对这个列表的所有元素应用这个操作

```
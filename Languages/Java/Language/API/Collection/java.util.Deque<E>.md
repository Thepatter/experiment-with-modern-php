*java.util.Deque<E>*
```java
void addFirst(E element);

void addLast(E element);

boolean offerFirst(E element);

boolean offerLast(E element);

// 将给定的对象添加到双端队列的头部或尾部。如果队列满了，前面两个方法将抛出一个 `IllegalStateException` ，而后面两个方法返回 false

E removeFirst();

E removeLast();

E pollFirst();

E pollLast();

// 如果队列不空，删除并返回队列头部的元素。如果队列为空，前面两个方法将抛出 `NoSuchElementException` ，而后面两个方法返回 `null`

E getFirst();

E getLast();

E peekFirst();

E peekLast();

// 如果队列非空，返回队列头部的元素，但不删除。如果队列空，前面两个方法将抛出一个 `NoSuchElementException` ，而后面两个方法返回 null

```
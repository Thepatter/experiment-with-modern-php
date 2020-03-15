### Reactor

#### 反应式编程

##### 概念

反应式编程本质上是函数式和声明式的。相对于描述一组将依次执行的步骤，反应是编程描述了数据将会流经的管道或者流。相对于要求将被处理的数据作为一个整体进行处理，反应式流可以在数据可用时立即开始处理

##### 反应式流

反应式流旨在提供无阻塞回压的异步流处理标准

它使我们能够并行执行任务，从而实现更高的可伸缩性。通过回压，数据消费者可以限制它们想要处理的数据数量，避免被过快的数据源所淹没

Java 的流通常都是同步的，并且只能处理有限的数据集。从本质上来说，它们只是使用函数来对集合进行迭代的一种方式。
反应式流支持异步处理任意大小的数据集，同样也包括无限数据集。只要数据就绪，它们就能实时地处理数据，并且能够通过回压来避免压垮数据的消费者。

反应式流规范可以总结为 4 个接口：Publisher、Subscriber、Subscription、Processor。

Publisher 负责生成数据，并将数据发送给 Subscription（每个 Subscriber 对应一个 Subscription）。Publisher 接口声明了一个方法 subscribe()，Subscriber 可以通过该方法向 Publisher 发起订阅

```java
public interface Publisher<T> {
	void subscribe(Subscriber<? super T> subscriber);
}
```

一旦 Subscriber 订阅成功，就可以接收来自 Publisher 的事件。这些事件是通过 Subscriber 接口上的方法发送的

```java
public interface Subscriber<T> {
	void onSubscribe(Subscription sub);
	void onNext(T item);
	void onError(Throwable ex);
	void onComplete();
}
```

Subscriber 的第一个事件是通过对 onSubscribe() 方法的调用接收的。Publisher 调用 onSubscribe() 方法时，会将 Subscription 对象传递给 Subscriber。通过 Subscription，Subscriber 可以管理器订阅情况

```java
public interface Subscription {
    void request(long n);
    void cancel();
}
```

Subscriber 可以通过调用 request() 方法来请求 Publisher 发送数据，或者通过调用 cancel() 方法表明它不再对数据感兴趣并且取消订阅。当调用 request() 时，Subscriber 可以传入一个 long 类型的数值以表明它愿意接受多少数据。这也是回压能够发挥作用的地方，以避免 Publisher 发送多于 Subscriber 能够处理的数据量。在 Publisher 发送完所请求数量的数据项之后，Subscriber 可以再次调用 request() 方法来请求更多的数据。

Subscriber 请求数据之后，数据就会开始流经反应式流。Publisher 发布的每个数据项都会通过调用 Subscriber 的 onNext() 方法递交给 Subscriber。如果有任何错误，就会调用 onError() 方法。如果 Publisher 没有更多的数据，也不会继续产生更多的数据，那么将会调用 Subscriber 的 onComplete() 方法来告知 Subscriber 它已经结束

Processor 接口，它是 Subscriber 和 Publisher 组合

```java
public interface Processor<T, R> extends Subscriber<T>, Publisher<R> {}
```

反应式流规范的接口本身并不支持以函数式的方式组成这样的流。Reactor 项目是反应式流规范的一个实现，提供了一组用于组装反应式流的函数式 API。Reactor 构成了 Spring 5 反应式编程模型的基础

Mono 和 Flux 都实现了反应式流的 Publisher 接口。Flux 代表具有零个、一个或多个（可能式无限个）数据项的管道。Mono 式一种特殊的反应式类型，针对数据项不超过一个的场景进行了优化01
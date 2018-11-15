## Observer 观察者模式（发送状态变化通知）

### Observer 模式登场角色

* `Subject` 观察对象角色表示观察对象。`Subject` 角色定义了注册观察者和删除观察者的方法。此外，还声明了“获取现在的状态”的方法
* `ConcreteSubject` 具体的观察对象角色。当自身状态发生变化后，它会通知所有已经注册的 `Observer` 角色


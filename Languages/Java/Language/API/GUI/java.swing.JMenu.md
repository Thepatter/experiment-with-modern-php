*java.swing.JMenu*
```java
JMenu(String label);

// 用给定标签构造一个菜单

JMenuItem add(JMenuItem item);

// 添加一个菜单项（或一个菜单）

JMenuItem add(String label);

// 用给定标签将一个菜单项添加到菜单中，并返回这个菜单项

JMenuItem add(Action a);

// 用给定动作将一个菜单项添加到菜单中，并返回这个菜单项

void addSeparator();

// 将一个分隔符行（separator line）添加到菜单中

JMenuItem insert(JMenuItem menu, int index);

// 将一个新菜单项（或子菜单）添加到菜单的指定位置

JMenuItem insert(Action a, int index);

// 用给定动作在菜单的指定位置添加一个新菜单项

void insertSeparator(int index);

// 将一个分隔符添加到菜单中

// index   添加分隔符的位置

void remove(int index);

void remove(JMenuItem item);

// 从菜单中删除指定的菜单项

```
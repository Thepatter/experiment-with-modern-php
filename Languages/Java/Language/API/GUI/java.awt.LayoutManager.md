*java.awt.LayoutManager*
```java
void addLayoutComponent(String name, Component comp);

// 将组件添加到布局中

// name        组件位置的标识符

// comp        被添加的组件

void removeLayoutComponent(Component comp);

// 从本布局中删除一个组件

Dimension preferredLayoutSize(Container cont);

// 返回本布局下的容器的首选尺寸

Dimension minimumLayoutSize(Container cont);

// 返回本布局中下容器的最小尺寸

void layoutContainer(Container cont);

// 摆放容器内的组件

```
*javax.swing.JPopupMenu*
```java
void show(Component c, int x, int y);

// 显示一个弹出菜单

// c       显示弹出菜单的组件

// x, y    弹出菜单左上角的坐标（c 的坐标空间内）

boolean isPopupTrigger(MouseEvent event);

// 如果鼠标事件是弹出菜单触发器，则返回 true

```
*java.awt.event.WindowListener*
```java
void windowOpened(WindowEvent e);

// 窗口打开后调用这个方法

void windowClosing(WindowEvent e);

// 在用户发出窗口管理器命令关闭窗口时调用这个方法。仅当调用 `hide` 或 `dispose` 方法后窗口才能关闭

void windowClosed(WindowEvent e);

// 窗口关闭后调用这个方法

void windowIconified(WindowEvent e);

// 窗口图标化后调用这个方法

void windowDeiconified(WindowEvent e);

// 窗口非图标化后调用这个方法

void windowActivated(WindowEvent e);

// 激活窗口后调用这个方法。只有框架或对话框可以被激活。通常，窗口管理器会对活动窗口进行修饰

void windowDeactivated(WindowEvent e);

// 窗口变为未激活状态后调用这个方法

```
*javax.swing.JMenuItem*
```java
JMenuItem(String label);

// 用给定标签构造一个菜单项

JMenuItem(Action a);

// 为给定动作构造一个菜单项

JMenuItem(String label, Icon icon);

// 用给定的标签和图标构造一个菜单项

JMenuItem(String label, int mnemonic);

// 用给定的标签和快捷键字符构造一个菜单项

// label       菜单项的标签

// mnemonic    菜单项的快捷键字符，在标签中这个字符下面会有一个下划线

void setAccelerator(KeyStroke k);

// 将 k 设置为这个菜单项的加速器。加速器显示在标签的旁边

void menuSelected(MenuEvent e);

// 在菜单被选择但尚未打开之前调用

void menuDeselected(MenuEvent e);

// 在菜单被取消选择并且已经关闭之后被调用

void menuCanceled(MenuEvent e);

// 当菜单被取消时被调用

```
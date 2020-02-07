*javax.swing.JToolBar*
```java
JToolBar();

JToolBar(String titleString);

JToolBar(int orientation);

JToolBar(String titleString, int orientation);

// 用给定的标题字符串和方向构造一个工具栏。`Orientation` 可以是 `SwingConstants.HORIZONTAL`（默认）或 `SwingConstants.VERTICAL`

JButton add(Action a);

// 用给定的动作名，图标，简要的说明和动作回调构造一个工具栏中的新按钮

void addSeparator();

// 将一个分隔符添加到工具栏的尾部

```
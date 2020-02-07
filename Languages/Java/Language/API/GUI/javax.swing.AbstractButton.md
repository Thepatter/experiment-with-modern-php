*javax.swing.AbstractButton*
```java
void setActionCommand(String s);

// 设置按钮及其模型的动作命令

void setAction(Action a);

// 为这个按钮或菜单项设置动作

void setHorizontalTextPosition(int pos);

// 设置文本对应图标的水平位置

// pos     `SwingConstants.RIGHT` 文本在图标的右侧或 `SwingConstants.LEFT` 文本在图片左侧

boolean isSelected();

void setSelected(boolean state);

// 获取或设置这个菜单项的选择状态（true 为选定）

void setMnemonic(int mnemonic);

// 设置按钮的快捷字符。该字符会在标签中以下划线的形式显示

void setDisplayedMnemonicIndex(int index);

// 将按钮文本中的 `index` 字符设定为带下划线。如果不希望第一个出现的快捷键字符带下划线，就可以使用这个方法

```
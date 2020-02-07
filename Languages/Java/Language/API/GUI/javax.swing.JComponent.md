*javax.swing.JComponent*
```java
void paintComponent(Grphics g);

// 覆盖这个方法来描述应该如何绘制自己的组件

FontMetrics getFontMetrics(Font f);

// 获取给定字体的度量。

ActionMap getActionMap();

// 返回关联动作映射键和动作对象的映射

InputMap getInputMap(int flag);

// 获得将按键映射到动作键的输入映射。flag 触发动作的键盘焦点条件

void revalidate();

// 重新计算组件的位置和大小

void setFont(Font f);

// 设置组件的字体

void setBorder(Border border);

// 设置这个组件的边框

JPopupMenu getComponentPopupMenu();

void setComponentPopupMenu(JPopup popup);

// 获取或设置用于这个组件的弹出菜单

boolean getInheritsPopupMenu();

void setInheritsPopupMenu(boolean b);

// 获取或设置 `inheritsPopupMenu` 特性。如果这个特性被设置或这个组件的弹出菜单为 null，则应用上一级弹出菜单

void setToolTipText(String text);

// 设置当鼠标停留在组件上时显示在工具提示中文本

```
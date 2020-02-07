*java.awt.GridBagConstraints*
```java
* `int gridx, gridy`
// 指定单元格的起始行和列。默认值为 0

* `int gridwidth, gridheight`
// 指定单元格的行和列的范围。默认值为 1

* `double weighx, weighty`
// 指定单元格扩大时的容量。默认值为 0

* `int anchor`
// 表示组件的单元格内的对齐方式。可以选择的绝对位置有：

`NORTHWEST`, `NORTH`, `NORTHEAST`, `WEST`, `CENTER`, `EAST`, `SOUTHWEST`, `SOUTH`, `SOUTHEAST`
// 或者各个方向上的相对位置

`FIRST_LINE_START`, `LINE_START`, `FIRST_LINE_ENO`, `PAGE_START`, `CENTER`, `PAGE_END`, `LAST_LINE_START`, `LINE_END`, `LAST_LINE_END`
// 如果应用程序有可能从右向左，或者自顶部至排列文本，就应该使用后者。默认值为 `CENTER`

* `int fill`
// 指定组件在单元格内的填充行为，取值为 `NONE`, `BOTH`, `HORIZONTAL`, 或者 `VERTICAL`。默认值为 `NONE`

* `int ipadx, ipady`
// 指定组件周围的 “内部” 填充。默认值为 0

* `Insets insets`
// 指定组件边框周围的 “外部” 填充。默认为不填充

GridBagConstraints(int gridx, int gridy, int gridwidth, int gridheight, double weightx, double weighty, int anchor, int fill, Insets insets, int ipadx, int ipady);

// 用参数中给定的所有域值构造 `GridBagConstraints`, Sun 建议这个构造器只用在自动代码生成器中，因为它会使得代码非常难于阅读

```
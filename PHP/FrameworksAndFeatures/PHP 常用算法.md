### PHP 常用算法

#### 无限极分类

```php
public function generateTree($list, $pk = 'id', $pid = 'pid', $child = 'city', $root = 1)
    {
        $tree     = array();
        $packData = array();
        foreach ($list as $data) {
            $packData[$data[$pk]] = $data;
        };
        foreach ($packData as $key => $val) {
            if ($val[$pid] == $root) {
                //代表跟节点, 重点一
                $tree[] = &$packData[$key];
            } else {
                //找到其父类,重点二
                $packData[$val[$pid]][$child][] = &$packData[$key];
            }
        }
        return $tree;
    }
```


### 安装软件
`sudo apt install wv`
### php中使用

```
$output = str_replace('.doc', '.txt', $filname);

shell_exec('/usr/bin/wvText ' . $filename . ' ' . $output);

$text = file_get_contents($output);

if (!mb_detect_encoding($text, 'UTF-8', true)) {
    $text = utf8_encode($text);
}

unlink($output);
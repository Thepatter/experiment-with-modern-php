### curl

curl 是常用命令行工具，用来请求 web 服务器。

#### 常用命令

```bash
# 等价于 wget
curl -o [save-file] [url]
# 自动跳转
curl -L [url]
# 显示头信息
curl -i [url]
# 仅显示头信息
curl -I [url]
# 显示通信过程，包括请求头
curl -v [url]
# 追踪通信过程
curl --trace [output-file] [url]
curl --trace-ascii [output-file] [url]
```


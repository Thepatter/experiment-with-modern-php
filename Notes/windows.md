### windows 使用

##### 常规使用

###### 去掉扩展显示器程序窗口丢失

打开丢失软件-->alt + space-->M-->方向拖拽-->鼠标左键/回车

#### powershell

##### 配置

###### 环境变量

```powershell
env
$env:HTTP_PROXY="http://127.0.0.1:10809"
```

###### 修改 host 文件

以管理员运行：

1. hosts 修改后释放 命令行：ipconfig/release
2. 重建本地DNS缓存 命令行：ipconfig /flushdns

#### wsl

##### ssh

卸载原来 openssh-server 并重装

```shell
apt autoremove openssh-server
apt install openssh-server
```

修改配置

```
# /etc/ssh/sshd_config
Port 2222
PasswordAuthentication yes
```

重启

```shell
sudo service ssh --full-restart
```






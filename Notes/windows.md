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






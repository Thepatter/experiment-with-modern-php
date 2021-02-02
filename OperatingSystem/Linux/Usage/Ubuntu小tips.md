### ubuntu 一些小 tips

#### 常规使用

##### 配置相关

###### 修改默认编辑器为 vim

```shell
sudo update-alternatives --config editor
```

###### 开启 cron 日志

```shell
# 修改 rsyslog
sodo vim /etc/rsyslog.d/50-default.conf
# 取消 cron 注释
cron.* /var/log/cron.log
# 重启 rsyslog
sudo service rsyslog restart
# 重启 crontab
sudo service cron restart
# sysstat 软件包
sudo apt-get install -y sysstat
```

##### server 运行图形界面

###### 安装配置

```
# xfce4 是桌面，xrdp 模拟器，slim 输出管理程序
sudo apt install xfce4 xrdp slim
```

###### 配置中文环境

```bash
# 安装中文语言包
sudo apt-get install  language-pack-zh-han*
# 运行语言支持检查
sudo apt install $(check-language-support)
修改配置文件
vim /etc/default/local
# 新增内容
LANG="zh_CN.UTF-8"
LANGUAGE="zh_CN:zh"
LC_NUMERIC="zh_CN"
LC_TIME="zh_CN"
LC_MONETARY="zh_CN"
LC_PAPER="zh_CN"
LC_NAME="zh_CN"
LC_ADDRESS="zh_CN"
LC_TELEPHONE="zh_CN"
LC_MEASUREMENT="zh_CN"
LC_IDENTIFICATION="zh_CN"
LC_ALL="zh_CN.UTF-8"
# 修改环境文件
vim /etc/environment
# 新增
LANG="zh_CN.UTF-8"
LANGUAGE="zh_CN:zh"
LC_NUMERIC="zh_CN"
LC_TIME="zh_CN"
LC_MONETARY="zh_CN"
LC_PAPER="zh_CN"
LC_NAME="zh_CN"
LC_ADDRESS="zh_CN"
LC_TELEPHONE="zh_CN"
LC_MEASUREMENT="zh_CN"
LC_IDENTIFICATION="zh_CN"
LC_ALL="zh_CN.UTF-8"
```

#### 运行排错相关

##### 系统错误

###### 引导错误 

无法启动虚拟机报错如下：

```
Kernel Panic - not syncing: VFS: Unable to mount root fs on unknown-block(0,0)
```

修复流程：

1. 启动后在 GRUB 界面选择『Advanced options for Ubuntu』

2. 点击可以恢复的内核进入系统

3. 执行新内核引导更新

   ```bash
   # sudo update-initramfs -u -k {version}
   sudo update-initramfs -u -k 4.15.0-136-generic
   # 更新 grub 引导
   sudo update-grub
   ```

   

##### 软件错误
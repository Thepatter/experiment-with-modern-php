## ubuntu 一些小 tips

### 修改默认编辑器为 vim
```shell
sudo update-alternatives --config editor
```

### 开启 cron 日志

```shell
# 修改 rsyslog
sodo vim /etc/rsyslog.d/50-default.conf
# 取消 cron 注释
cron.* /var/log/cron.log
# 重启 rsyslog
sudo service rsyslog restart
# 重启 crontab
sudo service cron restart
```


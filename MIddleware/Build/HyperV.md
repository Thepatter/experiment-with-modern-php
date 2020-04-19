### HyperV

#### 虚拟机

##### 使用 HyperV 管理虚拟机

###### 前置条件

1. 硬件（主板、CPU）支持 hyperV 虚拟化
2. 系统支持非家庭版本

###### 创建虚拟机

1. 使用 GUI 快速创建

2. 以管理员使用 powershell 命令行

    ```powershell
    # 返回 HyperV 命令列表
    Get-Command -Module hyper-v | Qut-GridView
    # 获取帮助
    Get-Help Get-VM
    # 列出虚拟机
    Get-VM
    # 返回已启动的虚拟机列表
    Get-VM | where {$_.State -eq 'Running'}
    # 列出所有处于关机状态的虚拟机
    Get-VM | where {$_.State -eq 'Off'}
    # 启动对应名称虚拟机
    Start-VM -Name <virtual-machine-name>
    # 关闭所有 vm
    Get-VM | where {$_.State -eq 'Running'} | Stop-VM
    # 关闭单个
    Stop-vm -name <virtual-machine-name>
    # 创建检查点
    Get-VM -Name <VM name> | Checkpoint-VM -SnapshotName <name for snapshot>
    # 创建新虚拟机
    $VMName = "VMNAME"
    $VM = @{
       Name = $VMName 
       MemoryStartupBytes = 2147483648
       Generation = 2
       NewVHDPath = "C:\Virtual Machines\$VMName\$VMName.vhdx"
       NewVHDSizeBytes = 53687091200
       BootDevice = "VHD"
       Path = "C:\Virtual Machines\$VMName"
       SwitchName = (Get-VMSwitch).Name
    }
    New-VM @VM
    ```

    不能在 Hyper-V 虚拟机中运行 poweroff 命令，会导致系统卡死，并且必须重新进 blos 里进行引导

#### NAT 网络

NAT 使用主计算机的 IP 地址和端口通过内部 Hyper-V  虚拟开关向虚拟机授予对网络资源的访问权限。

网络地址转换（NAT）是一种网络模式，旨在通过将一个外部 IP 地址和端口映射到更大的内部 IP 地址集来转换 IP 地址。

基本上，NAT 使用流量表将流量从一个外部（主机）IP 地址和端口号路由到与网络上的终结点（虚拟机、计算机和容器等）关联的正确内部 IP 地址，此外，NAT 允许多个虚拟机托管需要相同（内部）通信端口的应用程序，方法是将它们映射到唯一的外部端口

##### 创建 NAT 虚拟网络

###### 使用 Powershell 创建

1. 以管理员身份运行 powershell

2. 创建内部交换机

    ```powershell
    New-VMSwitch -SwitchName "SwitchName" -SwitchType Internal
    ```

3. 获取创建的虚拟交换机的 ifIndex

    ```powershell
    # 内部交换机的名称类似于 vEthernet (SwitchName)，ifIndex 为数字
    Get-NetAdapter
    ```

4. 配置 NAT 网关

    ```powershell
    Net-NetIPAddress -IPAddress <NAT Gateway IP> -PrefixLength <NAT Subnet Prefix Length> -InterfaceIndex <ifIndex>
    New-NetIPAddress -IPAddress 192.168.0.1 -PrefixLength 24 -InterfaceIndex 24
    ```

    * IPAddress

        NAT 网关 IP 指定要用作 NAT 网关 IP 的 IPv4 或 IPv6 地址。常规形式将为 a.b.c.1（172.16.0.1）。最后一个位置不一定必须是 1，但通常是（基于前缀长度）。通用网关 IP 为 192.168.0.1

    * PrefixLength

        NAT 子网前缀长度定义的 NAT 本地自我大小（子网掩码）。

        子网前缀为 0 到 32 之间的整数值，0 将映射到整个 Internet，32 将只允许映射一个 IP。常用值是 24 到 12，具体取决于需要附加到 NAT 的 IP 数，常用 PrefixLength 为 24 这是子网掩码 255.255.255.0

    * InterfaceIndex

        虚拟交换机接口索引

5. 配置 NAT 网络

    ```powershell
    New-NetNat -Name <NATOutsideName> -InternalIPinterfaceAddressPrefix <NAT subnet prefix>
    New-NetNat -Name MyNATnetwork -InternalIPInterfaceAddressPrefix 192.168.0.0/24
    ```

    * Name

        描述 NAT 网络的名称

    * InternalIPinterfaceAddressPrefix

        NAT 子网前缀，同时描述上述 NAT 网关 IP 前缀和上述 NAT 子网前缀长度，192.168.0.0/24

#### 检查点

虚拟化的最大优势是能够轻松地保存虚拟机的状态。在 Hyper-V 中，通过使用虚拟机检查点完成此操作。

1. 先创建虚拟机检查点
2. 然后进行软件配置更改、应用软件更新或安装新的软件。
3. 如果系统更改导致问题，可以将虚拟机恢复为创建检查点时其所处的状态

Windows 10 Hyper-V 包括两种类型的检查点：

* 标准检查点

    启动检查点时，获取虚拟机和虚拟机内存状态的快照。快照并非完整备份，并可能导致系统在 Active Directory 等不同节点之间复制数据时出现数据一致性。HyperV 只提供 Windows 10 之前的标准检查点（以前称为快照）

* 生产检查点

    使用卷影复制服务或文件系统冻结 Linux 虚拟机上创建虚拟机的数据一致性备份。没有获取任何虚拟机内存状态的快照

    默认情况下选择生产检查点，但可以使用 HyperV 管理器或 PowerShell 对该选择进行更改

##### 更改检查点类型

###### HyperV GUI 管理器

1. 打开 HyperV 管理器
2. 右键单击虚拟机，然后选择设置
3. 在『管理』下，选择检查点
4. 选择所需的检查点类型

###### PowerShell

管理员身份运行 powershell

```powershell
# 设置为标准检查点
Set-VM -Name <vmname> -CheckpointType Standard
# 设置为生产检查点（如果生产检查点失败，则创建标准检查点）
Set-VM -Name <vmname> -CheckpointType Production
# 设置为生产检查点（如果生产检查点失败，则不创建标准检查点）
Set-VM -Name <vmname> -CheckpointType ProductionOnly
```

##### 创建检查点

###### 使用 Hyper-V 管理器

1. 在 Hyper-V 管理器中，选择虚拟机
2. 右键单击虚拟机的名称，然后单击检查点
3. 当此过程完成时，检查点将 Hyper-V 管理器中的检查点下显示

###### 使用 PowerShell

```powershell
# 使用 CheckPoint-Vm 命令创建检查点
Checkpoint-VM -Name <VMName>
```

##### 应用检查点

###### 使用 Hyper-V 管理器

1. 在 HyperV 管理器中的虚拟机下，选择虚拟机
2. 在『检查点』部分中，右键单击想要使用的检查点， 然后单击应用
3. 将显示一个带由以下选项的对话框
    * 创建检查点并应用：在虚拟机应用以前的检查点之前创建新的检查点
    * 应用：仅应用已选择的检查点。不能撤销此操作
    * 取消：在不执行任何操作的情况下，关闭该对话

###### 使用 PowerShell

```powershell
# 查看虚拟机的检查点列表
Get-VMCheckpoint -VMName <VMName>
# 若要应用检查点，使用 Restore-VMCheckpoint 命令
Resotre-VMCheckpoint -Name <checkpoint name> -VMName <VMName> -Confirm:$false
```

##### 重命名检查点

在某个特定点上创建多个检查点，通过为其提供可识别名称易于创建检查点时记住有关系统状态的详细信息

默认情况下，检查点的名称是虚拟机       

```powershell
virtual_machine_name (MM/DD/YYY -hh:mm:ss AM\PM)
```

###### 使用 HyperV 管理器

1. 选择虚拟机
2. 单击检查点选择重命名
3. 输入新名称（小于 100 个字符，并且该字段不能为空）
4. 完成

###### 使用 powershell

```powershell
Rename-VMCheckpoint -VMName <virtual machine name> -Name <checkpoint name> -NewName <new checkpoint name>
```

##### 删除检查点

###### 使用 powershell

```powershell
Remove-VMCheckpoint -VMName <virtual machine name> -Name <checkpoint name>
```

##### 导出检查点

导出会将检查点捆绑为虚拟机，以便检查点可以移动到新的位置。导入后，检查点将还原为虚拟机。导出的检查点可用于备份

```powershell
Export-VMCheckpoint -VMName <virtual machine name> -Name <checkpoint name> -Path <path for export>
```

##### 配置检查点位置

如果虚拟机没有检查点，可以更改检查点配置和已保存状态文件的存储位置

用于存储检查点配置文件的默认位置是：

```powershell
%systemroot%\ProgramData\Microsoft\Windows\Hyper-V\Snapshots
```

1. 可在 Hyper-V 管理器中，右键单击虚拟机的名称，单击设置
2. 在管理部分，选择检查点或检查点文件位置，
3. 在检查点文件位置中，输入希望存储文件的文件夹路径
4. 单击应用以应用更改


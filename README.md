# SiteInfo
查看网站信息。
## 它能够做什么
SiteInfo工具采用PHP编写而成，该工具将会访问API.PHP来获取网站的客流量和InnoDB缓冲池命中率并输出。
## 它的特性
* 在短时间内解析日志，获取流量和命中率。
* 使用 PHP 搭建，提供美观的页面。
* 所有信息将会以 JSON 格式返回。
## API接口
Api.php 只允许用户使用 GET 方式获取数据。
### 客流量信息
URL: `api?action=viewcount`

将会返回如下格式的 JSON

`{"ViewCount":{"Y-M-D":"viewcount":……,"ipcount":……},……}`

### InnoDB缓冲池命中率信息
URL: `api.php?action=innodbinfo`

将会返回如下格式的 JSON

`{"InnoDBBufferHitRate": ……}`

### 同时请求
访问的模块可以用“|”来分割达到同时请求多个模块的行为，URL: `api.php?action=innodbinfo|viewcount`或`api.php?action=viewcount|innodbinfo`

将会返回以上两种 JSON 格式。

### webhook-deploy

把这个脚本放在服务器中，当github中的项目得到更新时，
github的hook发送一个post请求给设置好的url，
然后通过运行脚本实现服务器的同步github上的数据。

#### 使用方法

  通过composer安装

```bash
{
	"require": {
		"brittyu/webhook-deploy": "dev-master"
	}
}
```

添加自己的配置文件config.php

```bash
<?php

return $config =  [
    'base_dir' => __DIR__,
    'log_name' => 'webhook.log',
    'xiestorewebhook' => [
        'remote' => "origin",
        'branch' => 'master',
        'path' => '/your/server/path/',
        'secret' => 'your-key'
    ]
];

```

添加启动脚本

```bash
<?php

include "vendor/autoload.php";

use Webhook\Github;
use Webhook\ResolvePost;

$config = require_once "config.php";

$hook = new Github($config, new ResolvePost);
$hook->execute();

```

#### 更多信息

[My blog](http://brittyu.xyz)

#### License
[MIT]()

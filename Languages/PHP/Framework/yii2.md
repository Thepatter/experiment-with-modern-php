### YII2 Framework

#### 路由

##### rest 路由规则

* 不支持显式路由，要支持 `restful` 的路由必须在 `config/<mainConf.php>` 中的 `$config[components]` 下增加 

  ```php
  'urlManager' => [
  	'enablePrettyUrl' => true,
  	'enableStrictParsing' => true,
      // 显示脚本名称即 index.php
  	'showScriptName' => false,
      // 一个规则列表，用来规定如何解析和创建 URL
  	'rules' => [
  		[
  			'class' => 'yii\rest\UrlRule',
  			'controller' => '<rest>',
  		],
  	]
  ]
  ```

  但增加该规则后，原来的 `url` 规则（`http://<host>/index.php?r=<controllerName>/<action>` 即失效

##### 默认路由规则

* 默认 URL 规则为：

  ```
  http://<host>/index.php?r=<controllerName>/<action>
  ```

  1. `<controllerName>` ：

     网站根目录下 `<controllers>` 目录中的对应的 `<ControllerNameController.php>` 小写 `<ControllerName>`。`Controller` 文件以大驼峰命名。如果多个单词则用 `-` 连接每个单词小写

     ```
     RestController.php = rest/<action>
     RestUserController.php = rest-user/<action>
     ```

  2. `<action>`：

     `<ControllerNameController.php>` 中的以 `action` 前缀开头的 `public` 方法。方法以小驼峰命名

     如果有多个单词则使用 `-` 连接每个单词的小写

     ```
     actionSearchCode = <controller>/search-code
     actionSearch = <controller>/search
     ```

* 无法显示指定 `url` 请求是 `post` 和 `get`

#### 请求

##### 获取请求参数

* 不支持依赖注入，`action` 中的参数必须显示在 `url` 中指定

* 获取 `get/post` 请求参数

  ```php
  # 获取所有 get 参数，包含 r 路由参数
  $requestGetParams = Yii::$app->getRequest()->get();
  # 获取 get 指定 key
  $requestGetKeyValue = Yii::$app->getRequest()->get('key');
  # 获取 post 所有参数
  $requestPostParams = Yii::$app->getRequest()->post();
  # 获取 post 指定 key
  $requestPostKeyValue = Yii::$app->getRequest()->post('key');
  ```

#### 数据库操作

##### 原生 SQL

  ```php
  // 返回多行关联数组，没有结果返回空数组
  $post = Yii::$app->db->createCommand('SELECT * from post')->queryAll();
  // 返回第一行，无结果返回 false
  $post = Yii::$app->db->createCommand('SELECT * from post where id = 1')->queryOne();
  // 返回一列 (第一列)
  // 如果该查询没有结果则返回空数组
  $titles = Yii::$app->db->createCommand('SELECT title FROM post')
               ->queryColumn();
  // 返回一个标量值
  // 如果该查询没有结果则返回 false
  $count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM post')
               ->queryScalar();
  ```

##### 绑定参数

  ```php
  $post = Yii::$app->db->createCommand('SELECT * FROM post WHERE id=:id AND status=:status')
             ->bindValue(':id', $_GET['id'])
             ->bindValue(':status', 1)
             ->queryOne();
  ```

##### 非查询语句

  ```php
  // 方法返回执行 SQL 所影响到的行数
  Yii::$app->db->createCommand('UPDATE post SET status=1 WHERE id=1')
     ->execute();
  // UPDATE (table name, column values, condition)
  Yii::$app->db->createCommand()->update('user', ['status' => 1], 'age > 30')->execute();
  // DELETE (table name, condition)
  Yii::$app->db->createCommand()->delete('user', 'status = 0')->execute();
  // 插入多行 table name, column names, column values
  Yii::$app->db->createCommand()->batchInsert('user', ['name', 'age'], [
      ['Tom', 30],
      ['Jane', 20],
      ['Linda', 25],
  ])->execute();
  ```

##### 事务

  ```php
  $db = Yii::$app->db;
  $transaction = $db->beginTransaction();
  try {
      $db->createCommand($sql1)->execute();
      $db->createCommand($sql2)->execute();
      // ... executing other SQL statements ...
      
      $transaction->commit();
  } catch(\Exception $e) {
      $transaction->rollBack();
      throw $e;
  } catch(\Throwable $e) {
      $transaction->rollBack();
      throw $e;
  }
  ```

##### 查询构造器

  ```php
  $rows = (new \yii\db\Query())
      ->select(['order_no', 'type', 'point', 'created_at'])
              ->from(self::tableName())
              ->where(
                  'uid=:uid',
                  [':uid' => $uid]
              )
              ->orderBy([
                  'created_at' => SORT_DESC,
              ])
              ->limit($pageSize)
              ->offset($pageSize * ($page - 1))
              ->all();
  ```

  查询构造器返回一个关联数组

##### 打印SQL

  打印 SQL 典型例子，使用查询构造器必须克隆构造器

  ```php
  $query = (new Query())
              ->select(['order_no', 'type', 'point', 'created_at'])
              ->from(self::tableName())
              ->where(
                  'uid=:uid',
                  [':uid' => $uid]
              )
              ->orderBy([
                  'created_at' => SORT_DESC,
              ])
              ->limit($pageSize)
              ->offset($pageSize * ($page - 1))
              ->all();
  $sqlQuery = clone $query;
  $codeCountry = $query->one();
  \response([
      'rawSql' => $sqlQuery->createCommand()->getRawSql(),
      'sql' => $sqlQuery->createCommand()->getSql()
  ]);
  ```

  


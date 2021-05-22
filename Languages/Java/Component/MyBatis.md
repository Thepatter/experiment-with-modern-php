### MyBatis

半自动映射框架，需要手工匹配提供 POJO、SQL 和映射关系。

maven 坐标

```xml
<dependency>
	<groupId>org.mybatis</groupId>
	<artifactId>mybatis</artifactId>
	<version>3.3.0</version>
</dependency>
```

#### 组成

##### 构成组件

* SqlSessionFactoryBuilder

  根据配置信息或代码来生成 SqlSessionFactory 工厂接口。可以构建多个 SessionFactory。一旦构建了 SqlSessionFactory 它的作用已经完结，它的生命周期仅限于自动变量

* SqlSessionFactory

  用于创建 SqlSession，每次需要访问数据库，就需要通过 SqlSessionFactory 创建 SqlSession，SqlSessionFactory 生命周期对应整个生命周期，其责任唯一，使用单例。即每个数据库只对应一个 SqlSessionFactory

  ```java
  import org.apache.ibatis.io.Resources;
  import org.apache.ibatis.session.SqlSession;
  import org.apache.ibatis.session.SqlSessionFactory;
  
  // 生成 SqlSession
  public SqlSession getSqlSession() {
  	String resource = "mybatis-config.xml";
  	InputStream inputStram = Resources.getResourceAsStream(resource);
  	SqlSessionFactory sqlSessionFactory = new SqlSessionFactoryBuilder().build(inputStream);
      return sqlSessionFactory.openSession();
  }
  
  // 硬编码
  public SqlSession getSqlsession() {
      // 构建数据库连接池
      PooledDataSource dataSource = new PooledDataSource();
      dataSource.setDriver("com.mysql.jdbc.Driver");
      dataSource.setUrl("jdbc:mysql://localhost:3306/mybaits");
      dataSource.setUsername("root");
      dataSource.setPassword("secret");
      // 构建数据库事务方式
      TransactionFactory transactionFactory = new JdbcTransactionFactory();
      // 创建数据库运行环境
      Environment environment = new Environment("development", transactionFactory, dataSource);
      // 构建 Configuration 对象
      Configuration configuration = new Configuration(environment);
      // 注册上下文别名
      configuration.getTypeAliasRegistry().registerAlias("role", Role.class);
      // 加入映射器
      configuration.addMapper(RoleMapper.class);
      // 使用 SqlSessionFactoryBuilder 构建 SqlSessionFactory
      SqlSessionFactory sqlSessionFactory = new SqlSessionFactoryBuilder().build(configuration);
      return sqlSessionFactory.openSession();
  }
  ```

* SqlSession

  获取 Mapper 接口（通过 Mapper 接口的命名空间和方法名找到对应的 SQL，发送给数据库执行）；发送 SQL 去执行（直接通过命名信息去执行 SQL 返回结果，在 SqlSession 层可以通过 update、insert、select、delete 与 SQL 的 id 来操作 XML 中配置好的 SQL）并返回结果。

  SqlSession 接口类似于一个 JDBC 中的 Connection 接口对象。生命周期即一次请求操作。非线程安全。执行时支持事务，通过 commit、rollback 方法提交或回滚事务。需要在使用后关闭（否则连接资源将很快被耗尽）

  实现类：DefaultSqlSession 和 SqlSessionManager。

* SQL Mapper

  接口，由动态代理进行实现。最大范围与 SqlSession 相同，一般用于自动变量。它由一个 Java 接口和 XML 文件（注解）构成。用于配置 SQL 映射和语句、定义参数类型、描述缓存、定义查询结果和 POJO 的映射关系。发送 SQL 去执行并获取执行结果。

  实现方式包含：通过 XML 文件方式实现；通过接口配合注解实现；接口与 XML 文件配合实现
  
  MyBaits 在 Mapper 接口上使用了动态代理。当调用一个接口的方法时，会先通过接口的全限定名称和当前调用的方法名的组合得到一个方法  id，这个 id 的值就是映射 XML 中 namespace 和具体方法 id 的组合。可以在代理方法中使用 sqlSession 以命名空间的方式调用方法。通过这种方式将接口和 XML 文件中的方法关联起来。这种代理方式和常规代理的不同之处在于，这里没有对某个具体类进行代理，而是通过代理转化成了对其他代码的调用

##### 常用特性

###### 结果映射

* 关联嵌套结果查询（通过一次查询将结果映射到不同对象的方式）

  需要关联多个表将所有需要的值一次性查出来。支持自动映射（通过别名让 MyBatis 自动将值匹配到对应的字段上，可以多层嵌套：以：`property.name` 映射到属性的子属性上

* rusultMap 配置，在 XML 映射文件中配置结果映射。

  ```xml
  <!-- 关联属性子属性，重名列需要加前缀 -->
  <result property="role.id" column="role_id"/>
  ```

#### XML 配置

##### 使用方式

1. 使用 mybatis-config.xml 配置 mybaits 相关设置

2. 配置日志

   在 `src/main/resources` 下配置 `log4j.properties`

   ```properties
   log4j.rootLogger=ERROR, stdout
   log4j.logger.zyw.action.mybatis.mapper=TRACE
   log4j.appender.stdout=org.apache.log4j.ConsoleAppender
   log4j.appender.stdout.layout=org.apache.log4j.PatternLayout
   log4j.appender.stdout.layout.ConversionPattern=%5p [%t] - %m%n
   ```

   mybatis 日志实现中，包名实际上是 XML 配置中的 namespace 属性值一部分

3. 使用 mapper.xml 声明 SQL 方法

##### mybatis-config.xml

mybatis 配置层次不能颠倒顺序

```xml
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE configuration
        PUBLIC "-//mybatis.org//DTD Config 3.0//EN"
        "http://mybatis.org/dtd/mybatis-3-config.dtd">
<configuration>
    <!-- 属性 -->
    <properties/>
    <!-- 设置 -->
	<settings>
    	<setting name="logImpl" value="LOG4J"/>
        <setting name="mapUnderscoreToCamelCase" value="true"/>
    </settings>
    <!-- 类型命名 -->
    <typeAliases>
    	<package name="zyw.action.mybatis.model"/>
    </typeAliases>
    <!-- 类型处理器 -->
    <typeHandlers/>
    <!-- 对象工厂 -->
    <objectFactory/>
    <!-- 插件 -->
    <plugins/>
    <!-- 配置环境 -->
    <environments default="development">
    	<environment id="development">
            <!-- 事务管理器(使用 jdbc 事务管理) -->
            <transactionManager type="JDBC"/>
            <!-- 数据源 -->
            <dataSource type="UNPOOLED">
                <property name="driver" value="com.mysql.jdbc.Driver"/>
                <property name="url" value="jdbc:mysql://localhost:3306/mybatis"/>
                <property name="username" value="root"/>
                <property name="password" value="secret"/>
            </dataSource>
        </environment>
    </environments>
    <!-- 数据库厂商标识 -->
    <databaseIdProvider/>
    <mappers>
        <mapper resource="zyw/action/mybatis/mapper/CountryMappper.xml"/>
    </mappers>
</configuration>
```

###### `<properties>`

用于配置属性，MyBatis 提供 3 种配置方式：

* property 子元素
* properties 配置文件
* 程序参数传递

###### `<settings>`

|          配置项          |   类型    |           描述           |
| :----------------------: | :-------: | :----------------------: |
|         logImpl          | name 属性 | 指定使用 LOG4J 输出日志  |
| mapUndersocreToCamelCase | name 属性 | 下划线列名映射为驼峰属性 |

###### `<typeAliases>`

配置包的别名，配置后在需要用到全限定类名时，直接使用类名

###### `<environments>`

环境配置（数据库连接）

###### `<mappers>`

配置包含完整类路径的 xml 映射文件。起始路径为 `src/main/resources`。或者 mapper.xml 对应接口所在包名

```xml
<mappers>
	<package name="zyw.action.mybatis.mapper"/>
</mappers>
```

使用 package  配置会查找该包下所有接口，循环对接口进行：

1. 判断接口对应的命名空间是否已经存在，如果存在抛出异常
2. 加载接口对应的 XML 映射文档，将接口全限定名转换为路径，以 .xml 为后缀搜索 XML 资源，如果找到就解析 XML
3. 处理接口中的注解方法

##### Mapper.xml

2. mapper 映射（即 mybatis-config 配置下 `<mapper>` 配置项，起始路径为 `src/main/resources`，与接口方法一起使用时，接口定义返回类型必须和 XML 配置类型一致（由 resultType/resultMap 声明））

   ```xml
   <?xml version="1.0" encoding="UTF-8" ?>
   <!DOCTYPE mapper PUBLIC "-//mybatis.org//DTD Mapper 3.0//EN"
           "http://mybatis.org/dtd/mybatis-3-mapper.dtd">
   <mapper namespace="zyw.action.mybatis.mapper.UserMapper">
       <resultMap id="userMap" type="zyw.action.mybatis.model.SysUser">
           <id property="id" column="id"/>
           <result property="userName" column="user_name"/>
           <result property="userPassword" column="user_password"/>
           <result property="userEmail" column="user_email"/>
           <result property="userInfo" column="user_info"/>
           <result property="headImg" column="head_img" jdbcType="BLOB"/>
           <result property="createTime" column="create_time" jdbcType="TIMESTAMP"/>
       </resultMap>
   
       <select id="selectById" resultMap="userMap">
           select * from sys_user where id = #{id}
       </select>
       
       <select id="selectAll" resultType="zyw.action.mybatis.model.SysUser">
           select id,
                  user_name userName,
                  user_password userPassword,
                  user_email userEmail,
                  user_info userInfo,
                  head_img headImg,
                  create_time createTime
           from sys_user
       </select>
       
       <!-- 连接及嵌套属性,设置别名时使用 user.属性名 user 是 SysRole 中的属性-->
       <select id="selectRolesByUserId" resultType="zyw.action.mybatis.model.SysRole">
       	select r.id,
                  r.role_name     roleName,
                  r.enabled       enabled,
                  r.create_by     createBy,
                  r.create_time   crateTime,
                  u.user_name  as "user.userName",
                  u.user_email as "user.userEmail"
           from sys_user u
                    inner join sys_user_role ur on u.id = ur.user_id
                    inner join sys_role r on ur.role_id = r.id
           where u.id = #{userId}
       </select>
       
       <insert id="insert">
           insert into sys_user (id, user_name, user_password, user_email, user_info, head_img, create_time)
           values (#{id}, #{userName}, #{userPassword}, #{userEmail}, #{userInfo}, #{headImg, jdbcType=Blob},
                   #{createTime, jdbcType=TIMESTAMP})
       </insert>
       
       <!-- 适用于非自增主键获取主键值,将该值赋值给 id -->
       <insert id="insertGetKey">
           insert into sys_user (user_name, user_password, user_email, user_info, head_img, create_time)
           values (#{userName}, #{userPassword}, #{userEmail}, #{userInfo}, #{headImg, jdbcType=BLOB},
           #{createTime, jdbcType=TIMESTAMP})
           <selectKey keyColumn="id" resultType="Integer" keyProperty="id" order="AFTER">
               SELECT LAST_INSERT_ID()
           </selectKey>
       </insert>
   </mapper>
   ```


###### `<mapper>`

当 Mapper 接口和 XML 文件关联时，XML 文件 mapper 元素 namespace 值需配置成接口的全限定名称（接口可以配合 XML 使用，也可以配合注解来使用。XML 可以单独使用，注解必须在接口中使用）当只使用 XML 而不使用接口时，namespace 值可以设置为任意不重复的名称

|  配置项   | 类型 |         描述          |
| :-------: | :--: | :-------------------: |
| namespace | 属性 | 定义当前 xml 命名空间 |

###### `<resultMap>`

配置 Java 对象的属性和查询结果列的对应关系

|     配置项      |  类型  |                             描述                             |
| :-------------: | :----: | :----------------------------------------------------------: |
|       id        |  属性  |                           必填唯一                           |
|      type       |  属性  |            用于配置查询列所映射到的 Java 对象类型            |
|     extends     |  属性  |  选填，可以配置当前的 resultMap 继承自其他 resultMap 的 id   |
|   autoMapping   |  属性  | 选填（true/false），配置是否启用非映射字段自动映射（可以覆盖全局 autoMappingBehaviro） |
|   constructor   | 子元素 |                   配置使用构造方法注入结果                   |
| constructor.id  | 子元素 |         标记结果作为 id（唯一值），可以提高整体性能          |
| constructor.arg | 子元素 |                 注入到构造方法的一个普通结果                 |
|   association   | 子元素 |                           类型关联                           |
|   collection    | 子元素 |                           关联集合                           |
|  discriminator  | 子元素 |                         动态映射结果                         |
|      case       | 子元素 |                     基于某些值得结果映射                     |

*association/collection*

|     属性     |        类型        |                      含义                       |
| :----------: | :----------------: | :---------------------------------------------: |
|   property   | 嵌套属性名（必填） |            类中的引用其他类的属性名             |
| columnPrefix |     查询列前缀     | 配置后，子标签配置 result/column 时可以省略前缀 |
|  resultMap   |    定义结果类型    |             可以直接使用 resultMap              |
|   javaType   |  属性对应 Java 类  |                                                 |
|              |                    |                                                 |
|              |                    |                                                 |

*id/result 子元素标签包含属性相同，id 代表主键（唯一值）得字段（可以有多个）*

|   配置项    | 类型 |                             描述                             |
| :---------: | :--: | :----------------------------------------------------------: |
|   column    | 属性 |                         列名，列别名                         |
|  property   | 属性 |              映射属性，支持通过 . 属性嵌套赋值               |
|  javaType   | 属性 | Java 类完全限定类名，或类型别名（通过 typeAlias 配置或默认类型），如果映射到 JavaBean MyBatis 通常可以自动判断属性类型。如果映射到 HashMap，则需要明确指定 JavaType 属性 |
|  jdbcType   | 属性 | 列对应数据库类型，JDBC 类型仅仅需要对插入、更新、删除操作可能为空得列进行处理 |
| typeHandler | 属性 | 该属性可以覆盖默认类型处理器。这个属性值是类得完全限定名或类型别名 |

property 属性或别名需要与对象中属性名相同，实际匹配时，MyBatis 会先将两者都转换为大写形式，然后再判断是否相同。

###### `<select>`

查询语句标签

|        配置项        | 类型 |                             描述                             |
| :------------------: | :--: | :----------------------------------------------------------: |
|          id          | 属性 |              命名空间中唯一标识符，代表这条语句              |
| resultMap/resultType | 属性 | 设置返回值类型（声明 resultMap 的 Id 值或 resultMap 的类型） |
|                      | 内容 |      查询语句支持 `#{id}` 预编译参数方式，id 传入参数名      |

如果使用 resultType 来设置返回结果得类型，需要在 SQL 中为所有列名和属性名不一致列设置别名，通过设置别名使最终得查询结果列和 resultType 指定对象得属性名一致，实现自动映射

联表查询，返回结果包含其他类属性时，可以再返回类中新增一个来存储其属性，查询时使用嵌套

```java
@Data
public class SysRole {
    private SysUser user;
}
```

当参数是一个基本类型时，它在 XML 文件中对应的 SQL 语句只会使用一个参数。当参数是一个 JavaBean 类型时，它在 XML 文件中对应的 SQL 语句会有多个参数。对于多参数，可以建立 Map 传值（不推荐）或使用 @Param 注解；

* Map 类型作为参数：使用 Map 类型作为参数的方法，就是在 Map 中通过 key 来映射 XML 中 SQL 使用的参数值名字，value 用来存放参数值，需要多个参数时，通过 Map 的 key-value 方式传递参数值，由于这种方式还需要自己手动创建 Map 以及对参数进行赋值，其实并不简洁

  XML 语句中可用的参数只有0、1、param1、param2... 如果不使用 @Param 注解而在 xml 中直接使用具名参数会报错（除非使用 JavaBean）

* Param 参数，在接口使用 @Param 注解声明

###### `<insert>`

|     配置项      |      类型      |                             描述                             |
| :-------------: | :------------: | :----------------------------------------------------------: |
|       id        |      属性      |                             唯一                             |
|  parameterType  |  属性（可选）  | 即将传入语句参数的完全限定类名或别名。mybaits 可以推断传入语句的具体参数，因此不建议配置该属性 |
|   flushCache    |  属性默认true  |      任何时候只要语句被调用，都会清空一级缓存和二级缓存      |
|     timeout     |      属性      |   设置在抛出异常之前，驱动程序等待数据库返回请求结果的秒数   |
|  statementType  |                | 对于 STATEMENT，PREPARED，CALLABLE，MyBatis 会分别使用对应 Statement、PreparedStatement、CallableStatement。默认值为 PREPARED |
| useGeneratedKey | 属性默认 false | 如果设置为 true，MyBatis 会使用 JDBC 的 getGeneratedKeys 方法获取主键值后赋值给属性。 |
|   keyProperty   |      属性      | MyBatis 通过 getGeneratedKey 获取主键值后将要赋值的属性名。如果希望得到多个数据库自动生成的列，属性值也可以是以逗号分隔的属性名称列表 |
|    keyColumn    |      属性      | 仅对 INSERT 和 UPDATE 有用。通过生成的键值设置表中的列名，这个设置仅在某些数据库（如 PostgreSQL）中是必须的，当主键列不是表中的第一列时需要设置。如果希望得到多个生成的列，也可以是逗号分隔的属性名称列表 |
|   databaseId    |                | 如果配置了 databaseIdProvider，MyBatis 会加载所有不带 databaseId 的或匹配当前 databaseId 的语句。如果同时存在带 databaseId 和不带 databaseId 的语句，后者会被忽略 |

#### 注解方式

注解方式是将 SQL 语句直接写在接口上。对于需求比较简单的系统，效率较高，当 SQL 有变化时需要重新编译代码

##### @Select

基本注解的参数可以是字符串数组类型或字符串类型。mapUnderscoreToCamelCase 配置方式不需要手动指定别名，MyBatis 字段按照下划线转驼峰的方式自动映射别名

###### @Results、@Result、@ResultMap

XML 中 resultMap 元素对应 @Results 注解来实现属性映射（@Result 注解对应 XML 中的 result 元素。），3.3.1 开始，@Results 注解增加了 id 属性，设置 id 属性后，可以通过 id 属性引用一个 @Results 配置（使用 @ResultMap 注解引用）

```java
@Results(
	id = "roleResultMap", 
    value = {
		@Result(property = "id", column = "id", id = true),
    	@Result(property = "roleName", column = "role_name"),
		@Result(property = "enabled", column = "enabled"),
		@Result(property = "createdBy", column = "create_by"),
		@Result(property = "createTime", column = "create_time")
	})
@Select({"select id, role_name roleName, enabled, create_by createBy, 		create_time createTime ",
	"from sys_role ",
	"where id = #{id}"})
SysRole selectRoleById(Integer id);


@ResultMap("roleResultMap")
@Select("select * from sys_role")
List<SysRole> selectAll();
```

##### @Insert

仅插入只需使用该注解

###### @Options

返回自增主键时可以使用该注解声明主键属性

```java
@Insert({"insert into sys_role(role_name, enabled, create_by, create_time",
" values(#{roleName}, #{enabled}, #{createdBy}, #{createTime}, #{createBy}, #{createTime, jdbcType=TIMESTAMP})"})
@Options(useGeneratedKeys = true, keyProperty = "id")
```

###### @SelectKey

需要获取插入非自增主键时，使用该注解获取，对应 XML 中的 selectKey

```java
@Insert({"insert into sys_role(role_name, enabled, create_by, create_time)",
         " values(#{roleName}, #{enabled}, #{createdBy}, #{createTime, jdbcType=TIMESTAMP})"})
@SelectKey(statement = "SELECT LAST_INSERT_ID()", keyProperty = "id", resultType = Integer.class, before = false)
int insertGetId(SysRole sysRole);
```

##### @Update

```java
@Update("update sys_role set role_name = #{roleName}, enabled = #{enabled}, create_by = #{createdBy}, create_time = #{createTime, jdbcType=TIMESTAMP} where id = #{id}")
int updateById(SysRole sysRole);
```

##### @Delete

```java
@Delete("delete from sys_role where id = #{id}")
int deleteById(Integer id);
```

##### @SelectProvider

Provider 的注解中提供了两个必填属性 type 和 method。type 配置的是一个包含method 属性指定方法的类，这个类必须有空的构造方法，这个方法的值就是要执行的SQL 语句，并且 method 属性指定的方法的返回值必须是 String 类型

```java
@SelectProvider(type = PrivilegeProvider.class, method = "selectById")
SysPrivilege selectById(Integer id);
```

####  动态 SQL

MyBatis 的动态 SQL 在 XML 中支持的：`if`、`choose(when, oterwise)`、`trim(where、set)`、`foreach`、`bind`

##### xml 支持标签

* if

  通常用于 WHERE 语句中，通过判断参数值来决定是否使用某个查询条件。无法实现 if...else 逻辑

  ```xml
  <if test="userName != null and userName != ''">
  	and user_name like concat('%', #{userName}, '%')
  </if>
  ```

  必填属性 test，test 的属性值是一个符合 OGNL 表达式（结果为 true 或 false，`property != null` 或 `property == null` 适用于任何类型字段，用于判断属性值是否为空；`property != ''` 或 `property == ''` 仅适用于 String 类型的字段，用于判断是否为空串）

  插入数据时，在列的地方使用 if 时也需要在 values 部分增加 if。

* choose

  choose when otherwise 实现 if...else 逻辑。choose 至少有一个 when，0 或 1 个 otherwise。

  ```xml
  <select id="selectByIdOrUserName" resultType="zyw.action.mybatis.model.SysUser">
      select id, user_name userName, user_password userPassword,
      user_email userEmail, user_info userInfo, head_img headImg,
      create_time createTime from sys_user
      where 1 = 1
      <choose>
          <when test="id != null">
              and id = #{id}
          </when>
          <when test="userName != null and userName != ''">
              and user_name = #{userName}
          </when>
          <otherwise>
              and 1 = 2
          </otherwise>
      </choose>
  </select>
  ```

* where

  如果该标签包含的元素中有返回值，就插入一个 where；如果 where 后面的字符串是以 AND 和 OR 开头的就将它们删除（当 where 中的子元素 if 条件不满足时，where 语句不会出现在 SQL 中，满足时会自动去掉开头的 and/or 关键词）

  ```xml
  <select id="selectRole" resultType="zyw.action.mybatis.model.SysRole">
      select * from sys_role
      <where>
          <if test="roleName != null and roleName !=''">
              and role_name like concat('%', #{userName}, '%')
          </if>
          <if test="id != null and id > 0">
              and id = #{id}
          </if>
      </where>
  </select>
  ```

* set

  如果该标签包含的元素中有返回值，就插入一个 set；如果 set 后面的字符串以逗号结尾的，就将这个逗号删除

  ```xml
  <update id="updateRole">
      update sys_role
      <set>
          <if test="roleName != null and roleName !=''">
              role_name = #{roleName},
          </if>
          <if test="enabled != null">
              enabled = #{enabled},
          </if>
          id = #{id}
      </set>
      where id = #{id}
  </update>
  ```

* trim

  ```XML
  <trim prefix="WHERE" prefixOverride="AND | OR ">
  </trim>
  ```

  trim 标签包含如下属性：

  prefix/suffix：当 trim 元素内包含内容时，会给内容增加 prefix/suffix 指定前缀；prefixOverrides/suffixOverrides：当 trim 元素内包含内容时，会把内容中匹配的前缀/后缀字符串去掉；

* foreach

  实现 in 查询防止 sql 注入

  ```xml
  <select id="selectByIds">
      select * from sys_role where id in
      <foreach collection="list" open="(" close=")" separator="," item="id" index="i">
          #{id}
      </foreach>
  </select>
  ```

  |    属性    |                              值                              |
  | :--------: | :----------------------------------------------------------: |
  | collection |                   必填，要迭代循环的属性名                   |
  |    item    |             变量名，值为迭代对象中取出的每一个值             |
  |   index    | 索引的属性名，在集合数组情况下值为当前索引值，当迭代循环的对象是 Map 对象时，值为 Map 的 key |
  |    open    |                     循环内容开始的字符串                     |
  |   close    |                     循环内容结尾的字符串                     |
  | separator  |                       每次循环的分隔符                       |

  collection 值依据传入参数类型（集合：list，数组：array），可以使用 param 来具名引用。传入参数为 map 时，将 collection 值指定为 map 中的 key。

  MyBatis 开始支持批量新增回写主键值的功能，这个功能首先要求数据库主键值为自增类型，同时还要求该数据库提供的JDBC驱动可以支持返回批量插入的主键值（JDBC 提供了接口，但并不是所有数据库都完美实现了该接口），因此到目前为止，可以完美支持该功能的仅有 MySQL 数据库。**使用该功能时，@Param 值和 foreach 值必须为 `list`，否则插入后报错找不到主键值**

  使用 map 更新数据库一行数据（map key 为属性名，value 为对应值）

  ```xml
  <update id="updateRoleMap">
      update sys_role set
      <foreach collection="_parameter" item="val" index="key" separator=",">
          ${key} = #{val}
      </foreach>
      where id = #{id}
  </update>
  ```






### Spring Data JPA

#### JPA

Java Persistence API，java 持久层 API，是 JDK 5.0 注解或 XML 描述对象-关系表的映射关系，并将运行期的实体对象持久化到数据库中，JPA 包含：

* 一套 API 标准，在 *javax.persistence* 包下面，用来操作实体对象，执行 CRUD 操作
  
* 面向对象的查询语言，Java Persistence Query Language

* Object relational metadata 元数据映射，JPA 支持 XML 和 JDK 5.0 注解两种元数据的格式
  
#### Spring Data

Spring Data 项目提供一个一致的、基于 Spring 的数据访问编程模型，同时仍然保留底层数据存储的特殊性，支持关系数据库、非关系数据库、云数据服务，数据访问对象实现了对物理数据层的抽象，操作上主要有：

* 提供模板操作：Spring Data Redis 和 Spring Data Riak

* 强大的 Repository 和定制的数据存储对象的抽象映射

* 对数据访问对象的支持

Spring Data Common 是 Spring Data 所有模块的公共部分，提供跨 Spring 数据项目的共享基础设施，包含技术中立的库接口以及一个 Java 类的元数据模型

#### Spring Data JPA

底层使用了 Hibernate 的 JPA 技术实现，引用 JPQL 查询语言，属于 Spring 生态

#### 使用

Spring Repository 的 delete 和 save 方法，会先进行查询，在进行保存和删除，两次操作在事务中进行。使用 JPA，对应实现为 *org.springframework.data.jpa.repository.support.SimpleJpaRepository*，如果使用 NoSQL，则在对应 Model 里

Repository、CurdRepository、PageAndSortingRepository 兼容 SQL 和 NoSQL。JpaRepository 则专注于关系数据库的抽象封装

###### Repository 实现

*SimpleJpaRepository* 是 JPA 整个关联数据库的所有 Repository 的接口实现类，也是 Spring JPA 动态代理的实现类。

##### 查询

###### 查询策略

JPA Repository 实现采用动态代理。支持使用方法名称进行操作和使用 @Query 手动定义的查询，方法的查询策略可以通过 @EnableJpaRepositories 注解配置查询策略

```java
/** 
    支持:
    CREATE 直接根据方法名进行创建，根据方法名称的构造进行尝试，从方法名中删除给定的一组已知前缀，并解析该方法的其余部分，如果方法名不符合规则，启动时会报异常
    USE_DECLARED_QUERY 声明方式创建，启动时会尝试查询一个声明的查询，如果未找到就抛出异常，查询可以由某处注释或其他方法声明
    CREATE_IF_NOT_FOUND 默认，结合方法名和声明
*/
@EnableJpaRepositories(queryLookupStrategy = QueryLookupStrategy.Key.CREATE)
```

###### 查询参数

```java
public interface UserRepository extends JpaRepository<User, Long> {
    // 使用 @Param 注解具名参数绑定
    @Query("select u from User u where u.firstname = :firstname or u.lastname = :lastname")
    User findByLastnameOrFirstname(@Param("Lastname") String lastname, @Param("firstname") String firstname);
    // 使用 @Modifying 注解实现只需要参数绑定的 update 查询执行
    @Modifying
    @Query("update User u set u.firstname = ? where u.lastname = ?")
    int setFirstnameFor(String firstname, String lastname);
}
```

###### 查询结果扩展

一般情况下返回的字段和 DB 查询结果的字段是一一对应的，需要返回一些指定的字段时，允许对专用返回类型进行建模

```java
// 声明接口，包含要返回的属性方法
interface NamesOnly {
    String getFirstName();
    String getLastName();
}
// 或者使用 Dto 实体类
class NamesOnlyDto {
    private final String firstName, lastName;
    NamesOnlyDto(String firstName, String lastName) {
        this.firstName = firstName;
        this.lastName = lastName;
    }
    String getFirstName() { return this.firstName; }
    String getLastName() { return this.lastName; }
}
// 在 Repository 里直接用这个接口或 Dto 实体类接收结果
Collection<NamesOnly> findByLastName(String lastname);

// 动态 projections，通过泛化，根据不同的业务情况返回不同的字段集合

interface PersonRepository extends Repository<Person, Integer> {
    Collection<T> findByLastName(String lastname, Class<T> type);
}

// 调用方通过 class 类型动态指定返回不同字段的结果集
void dynamicReturn(PersonRepository people) {
    // 包含全部字段，使用原始 entity
    Collection<Person> persons = people.findByLastname("tom", Person.class);
    // 返回指定字段，指定 Dto
    Collection<NamesOnlyDto> names = people.findByLastname("tom", NamesOnlyDto.class);
}
```

###### 排序

在 findAll 中传入 Sort

```java
repository.findAll(new Sort(new Sort.Order(Sort.Direction.ASC, "column")));

// 使用 native query
@Query("select * from user where name = ? order by ?", nativeQuery = true)
List<User> findByFirstName(String firstName, String sort);
repository.findByFirstName("Tome", "age");
// 使用 sort
@Query("select u.id, LENGTH(u.firstname) as fn_length from User u where u.lastname like ?%")
List<Object[]> findByAsArrayAndSort(String lastname, Sort sort);
repository.findByAsArrayAndSort("tom", new Sort("fn_length"));
```

###### 分页

```java
public interface UserRepository extends JpaRepository<User, Long> {
    @Query(value = "select u from User u where u.lastname = ?")
    Page<User> findByLastname(String lastname, Pageable pageable);

    @Query(value = "select * from user where first_name = ? /* #pageable# */", countQuery = "select count(*) from user where first_name = ?", nativeQuery = true)
    Page<User> findByFirstName(String firstName, Pageable pageable);
}

repository.findByLastname("tom", new PageRequest(1, 10));
repository.findByFirstName("tom", new PageRequest(1, 10, Sort.Direction.DESC, "last_name"));
```

###### N+1 SQL

当使用 @ManyToMany、@ManyToOne、@OneToMany、@OneToOne 关联关系时，SQL 执行时由一条主表查询和 N 条子表查询组成，会执行 N + 1 条 SQL 语句

Spring Data JPA 引入了 EntityGraph 来解决 N + 1 SQL 问题，在 Entity 定义 @NamedEntityGraph，在 Repository 查询方法中使用 @EntityGraph 注解

```java
@NamedEntityGraph(name = "User.addressEntityList", atttributeNodes = {
	@NamedAttributeNode("addressEntityList"),
	@NamedAttributeNode("userBlogEntityList")
})
@Entity
public class User implements Serializable {
	@Id
	private Integer id;
	@OneToOne(optional = false)
	@JoinColumn(referencedColumnName = "id", name = "address_id", nullable = false)
	private UserReceivingAddressEntity addressEntityList;
    @OneToMany
    @JoinColumn(name = "create_user_id", referencedColumnName = "id")
    private List<UserBlogEntity> userBlogEntityList;
}

public interface UserRepository extends JpaRepository<User,Integer> {
    @Override
    @EntityGraph(value = "User.addressEntityList") // value 为 @NamedEntityGraph 中 Name
    List<UserInfoEntity> findAll();
}
```


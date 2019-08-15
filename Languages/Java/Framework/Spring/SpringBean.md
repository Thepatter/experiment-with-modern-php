## Spring Bean

### bean 的生命周期

在传统的 Java 应用中，bean 的生命周期很简单。使用 Java 关键字 new 进行 bean 实例化，然后该 bean 就可以使用了。一旦该 bean 不再被使用，则由 Java 自动进行垃圾回收。

*Spring bean 生命周期*

![](./Images/SpringBean生命周期.png)

每个阶段都可以针对 Spring 如果管理 bean 进行个性化定制

* Spring 对 bean 进行实例化
* Spring 将值和 bean 的引用注入到 bean 对应的属性中
* 如果 bean 实现了 BeanNameAware 接口，Spring 将 bean 的 ID 传递给 setBeanName() 方法
* 如果 bean 实现了 BeanFactoryAware 接口，Spring 将调用 setBeanFactory() 方法，将 BeanFactory 容器实例传入
* 如果 bean 实现了 ApplicationContextAware 接口，Spring 将调用 setApplicationContext() 方法，将 bean 所在的应用上下文的引用传入进来
* 如果 bean 实现了 BeanPostProcessor 接口，Spring 将调用它们的 postProcessBeforeInitialization() 方法
* 如果 bean 实现了 InitializingBean 接口，Spring 将调用它们的 afterPropertiesSet() 方法。如果 bean 使用 init-method 声明了初始化方法，该方法也会被调用
* 如果 bean 实现了 BeanPostProcessor 接口，Spring 将调用它们的 postProcessAfterInitialization() 方法
* 此时，bean 已经准备就绪，可以被应用程序使用了，它们将一直驻留在应用上下文中，直到该应用上下文被销毁
* 如果 bean 实现了 DisposableDean 接口，Spring 将调用它的 destroy() 接口方法。同样，如果 bean 使用 destroyMethod 声明了销毁方法，该方法也会被调用

### 装配 bean

在 Spring 中，对象无需自己查找或创建与其所关联的其他对象。容器负责把需要相互协作的对象引用赋予各个对象。创建应用对象之间协作关系的行为通常为**装配**，这是依赖注入的本质。当描述 bean 如何进行装配时，Spring 具有非常大的灵活性，提供了三种主要的装配机制：

* XML 中进行显式配置
* 在 Java 中进行显式配置
* 隐式的 bean 发现机制和自动装配

Spring 的配置风格是可以互相搭配的，建议尽可能地使用自动配置的机制。显示配置越少越好。当必须要显式配置 bean 时，推荐使用类型安全并且比 XML 更加强大的 JavaConfig。

#### 自动化装配 bean

Spring 从两个角度来实现自动化装配：

* 组件扫描（component scanning）

  Spring 会自动发现应用上下文中所创建的 bean

* 自动装配（autowiring)

  Spring 自动满足 bean 之间的依赖

`@Component` 注解，表明该类会作为组件类，并告知 Spring 要为这个类创建 bean。组件扫描默认不启用，需要显式配置 Spring

*配置类*

```java
import org.springframework.context.annotation.compoentScan;
import org.springframework.context.annotation.Con;

@Configuration
@ConponentScan
public class CDPlayerConfig() {}
```

`CDPlayerConfig` 类并没有显式地声明任何 bean，只不过它使用 `@ComponentScan` 注解，这个注解能够在 Spring 中启用组件扫描。如果没有其他配置，`@ComponentScan` 默认会扫描与配置类相同的包，查找带有 `@Component` 注解的类。可以使用 XML 来启用组件扫描，可以使用 Spring Context 命名空间的 

```xml
<?xml version="1.0" encoding="UTF-8"?>
<beans xmlns="http://www.springframework.org/schema/beans"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xmlns:Context="http://www.springframework.org/schema/context"
  xsi:schemaLocation="http://www.springframework.org/schema/beans"
	http://www.springframework.org/schema/beans/spring-beans.xsd
	http://www.springframework.org/schema/context>
	http://www.springframework.org/schema/context/spring-context.xsd"?
  <context:component-scan base-package="beanPackage"/>
</beans>
```

##### 为组件扫描的 bean 命名

Spring 应用上下文中所有的 bean 都会给定一个 ID。如果想为这个 bean 设置不同的 ID，可以将 ID 值传递给 `@Component` 注解。或者使用 Java 依赖注入规范（Java Dependency Injection）中提供的 @Named 注解来为 bean 设置 ID。Spring 支持将 @Named 作为 @Component 注解的替代方案。两者之间有一些差异，但大多数场景中，它们可以互相替换。

```java
@Component("lonelyHeartsClub")
public class SgtPeppers implements CompactDisc{}
@Named("lonelyHeartsClub")
public class SgtPeppers implements CompactDisc{}
```

##### 设置组件扫描的基础包

如果没有为 `@ComponentScan` 设置任何属性。按照默认规则，它会以配置类所在的包作为基础包来扫描组件。可以在 `@ComponentScan` 的 value 属性中指明包的名称：

```java
@Configuration
@ComponentScan("soundsystem")
public class CDPlayerConfig {}
@Configuration
@ComponentScan(basePackages={"soundsystem", "video"})
public class CDPlayerConfig {}
```

除了将包设置为简单的 String 类型外，@ComponentScan 还提供了另外一种方法，将其指定为包中所包含的类或接口

```java
@Configuration
@ComponentScan(basePackageClasses={CDPlayer.class, DVDPlayer.class})
public class CDPlayerConfig() {}
```

##### bean 自动装配

自动装配就是即是让 `Spring` 自动满足 bean 依赖的一种方法，在满足依赖的过程中，会在 Spring 应用上下文中寻找匹配某个 bean 需求的其他 bean。为了声明要进行自动装配，可以使用 Spring 的 `@Autowired` 注解。将 `@Autowired` 配置在方法上时，Spring 会尝试满足方法参数上所声明的依赖，假如有且只有一个 bean 匹配依赖需求的化，那么这个 bean 将会被装配进来。如果没有匹配的 bean，那么在应用上下文创建的时候，Spring 会抛出一个异常。为了避免异常的出现，可以将 `@Autowired` 的 `required` 属性设置为 false：

```java
@Autowired(required=false)
public CDPlayer(CompactDisc cd) {
	this.cd = cd;
}
```

将 `required` 属性设置为 false 时，Spring 会尝试执行自动装配，但是如果没有匹配的 bean，Spring 将会让这个 bean 处理未装配的状态。required 属性为 false 时，如果代码中没有进行 null 检查，这个处于未装配状态的属性可能会出现 `NullPointerException` 。如果有多个 bean 都能满足依赖关系，Spring 将会抛出异常，表明没有明确指定要选择那个 bean 进行自动装配

@Autowired 是 Spring 特有的注解。如果不愿意在代码中使用 Spring 的特定注解来完成自动装配任务，可以考虑将其替换为 `@Inject`。`@Inject` 注解来源于 Java 依赖注入规范，在自动装配中，Spring 同时支持 `@Inject` 和 `@Autowired` 。它们有一些差别，但多数场景下，可以互相替换

#### 通过 Java 代码装配 bean

如果要将第三方库中的组件装配到应用中，在这种情况下，是没有办法再它类上添加 `@Component` 和 `@Autowired` 注解的，因此不能使用自动化装配的方案。这种情况下，必须采用显式装配的方式：Java 和 XML。在进行显式配置时，JavaConfig 是更好的方案。JavaConfig 是配置代码，不应该包含任何业务逻辑，也不应该侵入到业务逻辑代码之中。一般建议将 JavaConfig 放到单独的包中。

创建 JavaConfig 类为其添加 `@Configuration` 注解，表明这是一个配置类，包含在 Spring 应用上下文中如何创建 bean 的细节

##### 声明 bean

要在 JavaConfig 中声明 bean，需要编写一个方法，这个方法会创建所需类型的实例，然后给这个方法添加 `@Bean` 注解，默认情况下，bean 的 ID 与带有 @Bean 注解的方法名一样的。可以通过 name 属性指定一个不同的名字

```java
@Bean(name="lonelyHeartsClubBand")
public CompactDisc sgtPeppers() {
    return new SgtPeppers();
}
```

#### 通过 XML 装配 bean

##### 声明


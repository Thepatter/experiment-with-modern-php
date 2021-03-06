### 创建和销毁对象

#### 用静态方法代替构造器

对于类而言，为了让客户端获取它自身的一个实例，最传统的方法就是提供一个**公有构造器**。类还可以提供一个公有的静态工厂方法，它只是一个返回类的实例的静态方法

##### 静态工厂方法优势

###### 静态工厂方法可以指定适当名称

一个类只能有一个带有指定签名的构造器，通过提供两个构造器，它们的参数列表只在参数类型的顺序上有所不同来规避。但面对这样的 API 调用时候实际上容易混淆。由于静态工厂方法有名称，所以它们不受上述限制。_当一个类需要多个带有相同签名的构造器，就用静态工厂方法代替构造器，并且仔细地选择名称以便提出静态工厂方法之间的区别_

###### 不必每次调用它们的时候都创建一个新对象

这使得不可变类可以使用预先构建好的实例，或者将构建好的实例缓存起来，进行重复使用，从而避免创建不必要的重复对象。如果程序经常请求创建相同的对象，并且创建对象的代价很高，则此可以提高性能。静态工厂方法能够为重复调用返回相同对象，这样有助于类总能严格控制在某个时刻哪些实例应该存在。这种类被称作**实例受控的类**。

编写实例受控的类有几个原因：实例受控的类可以确保它是一个 Singleton 或者是不可实例化的。它还使得不可变的的值类可以确保不会存在两个相等的实例（即：当且仅当 a==b 时，a.equals(b) 才为 true。枚举类型保证了这一点）

###### 可以返回原返回类型的任何子类型的对象

接口可以返回对象，同时又不会使对象的类变成公有的。以这种方式隐藏实现类会使接口变得非常简洁。这项技术适用于**基于接口**的框架，因为在这种框架中，接口为静态工厂方法提供了自然返回类型。在 Java 8 之前，接口不能有静态方法，因此按照惯例，接口 `Type` 的静态工厂方法被放在一个名为 `Types` 的不可实例化的伴生类中。如：`Java Collections Framework` 的集合接口有 45 个工具实现，分别提供了不可修改的集合、同步集合等。几乎所有这些实现都通过静态工厂方法在一个不可实例化的类（`java.util.Collections`）中导出。所有返回对象的类都是非公有的。

从 Java 8 开始，接口中不能包含静态方法的这一限制成为历史，因此一般没有任何理由给接口提供一个不可实例化的伴生类。已经被放在这种类中的许多公有的静态成员，应该被放到接口中去。不过仍然有必要将这些静态方法背后的大部分实现代码，单独放进一个包级私有类中。在 Java 8 中仍要求接口的所有静态成员都必须是公有的。在 Java 9 中允许接口有私有的静态方法，但是静态域和静态成员类仍然需要是公有的

###### 所返回的对象的类可以随着每次调用而发生变化，这取决于静态工厂方法的参数值

只要是已声明的返回类型的子类型，都是允许的。返回对象的类可能随着发行版本的不同而不同如：EnumSet没有公有构造器，只有静态工厂方法。在 OpenJDK 实现中，它返回两种子类之一的实例，具体则取决于底层枚举类型的大小：如果元素有 64 个或更少，返回一个 `RegalarEnumSet` 实例，用单个 long 进行支持；如果枚举类型有 65 个或者更多元素，工厂就返回 `JumboEnumSet` 实例，用一个 long 数组进行支持

###### 方法返回的对象所属的类，在编写包含该静态工厂方法的类时可以不存在

这种灵活的静态工厂方法构成了**服务提供者框架（Service Provider Framework）**的基础。服务提供者框架系统结构：**多个服务提供者实现一个服务，系统为服务提供者的客户端提供多个实现，并把它们从多个实现中解耦出来。服务提供着框架中有三个重要的组件：服务接口（Service Interface），这是提供者实现的；提供者注册 API （Provider Registration API），这是提供者用来注册实现的；服务访问 API （Service Access API），这是客户端用来获取服务的实例。服务访问 API 时客户端用来制定某种选择实现的条件。如果没有这样的规则，API 就会返回默认实现的一个实例，或者允许客户端遍历所有可用的实现。服务访问 API 是灵活的静态工厂，构成了服务提供者框架的基础。服务提供者接口（Service Provider Interface）是可选的，它表示产生服务接口之实例的工厂对象。如果没有服务提供者接口，实现就通过反射方式进行实例化。** 对于 JDBC 来说，Connection 就是其服务接口的一部分，`DriverManager.registerDriver` 是提供者注册 API，`DriverManager.getConnection` 是服务访问 API，`Driver` 是服务提供者接口

服务提供者框架模式有着多种变体：服务访问 API 可以返回比提供者需要的更丰富的服务接口，即桥接模式。依赖注入框架可以被看作是一个强大的服务提供者。从 Java 6 版本开始，Java 平台就提供了一个通用的服务提供者框架 `java.util.ServiceLoader`，因此不需要一般来说也不应该再自己编写了。JDBC 不用  `ServiceLoader` 因为 JDBC 早出现于 `ServiceLoader`

##### 静态工厂方法缺点

###### 类如果不含公有或者受保护的构造器，就不能被子类化

###### 静态工厂方法的第二个缺点在于，程序员很难发现它们

它们没有像构造器那样在 API 文档中明确标识出来。因此对于提供了静态工厂方法而不是构造器的类来说，要想查明如果实例化一个类是很困难的。**静态工厂方法的常用名称**：

* `from` 

  类型转换方法，只有单个参数，返回该类型的一个相对应的实例

  ```java
  Date d = Date.from(instant);
  ```

* `of` 

  聚合方法，带有多个参数，返回该类型的一个实例，把它们合并起来

  ```java
  Set<Rand> faceCards = EnumSet.of(JACK, QUEEN, KING);
  ```

* `valueOf` 

  比 `from` 和 `of` 更繁琐的一种替代方法

  ```java
  BigInteger prime = BigInteger.valueOf(Integer.MAX_VALUE);
  ```

* `instance` 或 `getInstance` 

  返回的实例是通过方法的参数来描述的，但是不能说与参数具有同样的值

  ```java
  StackWalker luke = StackWalker.getInstance(options);
  ```

* `create` 或 `newInstance` 

  像 `instance` 和 `getInstance` 一样，但 `create` 或者  `newInstance` 能够确保每次调用都返回一个新的实例

  ```java
  Object newArray = Array.newInstance(classObject, arrayLen);
  ```

* `getType`

  像 `getInstance` 一样，但是在工厂方法处于不同的类中的时候使用。Type 表示工厂方法能返回的对象类型

  ```java
  FileStore fs = Files.getFileStore(path);
  ```

* `newType` 

  像 `newInstance` 一样，但是在工厂方法处于不同的类中的时候使用。Type 表示工厂方法所返回的对象类型

  ```java
  BufferedReader br = Files.newBufferedReader(path);
  ```

* `type` 

  `getType` 和 `newType` 的简版
  
  ```java
  List<Complaint> litany = Collections.list(legacyLitany)
  ```

#### 遇到多个构造器参数时要考虑使用构造器

静态工厂和构造器有个共同的局限性：不能很好地扩展到大量的可选参数。对于这种类：

###### 重叠构造器模式

可采用 **重叠构造器（telescoping constructor）**，在这种模式下，提供的第一个构造器只有必要的参数，第二个构造器有一个可选参数，第三个构造器有两个可选参数，依次类推，最后一个构造器包含所有可选参数。重叠构造器模式虽然可行，但当有许多参数的时候，客户端代码会很难编写，并且比较难以阅读。

###### JavaBeans 模式

还可以采用 **JavaBeans 模式**，在这种模式下，先调用一个无参数构造器来创建对象，然后再调用 `setter` 方法来设置每个必要的参数，以及每个相关的可选参数。但 JavaBeans 模式自身由很严重的缺点，因为构造过程被分到了几个调用中，**在构造过程中 JavaBean 可能处于不一致的状态**类无法仅仅通过检验构造器参数的有效性来保证一致性。试图使用处于不一致状态的对象将会导致失败，这种失败与包含错误代码大相径庭，调试十分困难。**JavaBeans 模式使得把类做成不可变的可能性不复存在，需要程序员付出额外精力来确保它的线程安全**当对象的构造完成，并且不允许在冻结之前使用时，通过手工「冻结」对象可以弥补这些不足，但是这种方式十分笨拙，在实践中很少使用。此外，它甚至会在运行时导致错误，因为编译器无法确保程序员会在使用之前先调用对象上的 `freeze` 方法进行冻结

###### Builder 模式

使用 **Builder 模式** 既能保证像重叠构造器模式那样的安全性，也能保证像 JavaBeans 模式那么好的可读性。它不直接生成想要的对象，而是让客户端在 `builder` 对象上调用类似于 `setter` 的方法，来设置每个相关的可选参数。最后，客户端调用无参的 `build` 方法来生成通常时不可变的对象。这个 `builder` 通常是它构建的类的静态成员类

```java
public class NutritionFacts {
  	private final int servingSize;
  	private final int servings;
  	private final int calories;
  	private final int fat;
  	private final int sodium;
  	private final int carbohydrate;
  	public static class Builder {
      	private final int servingSize;
      	private final int servings;
      	private int calories = 0;
      	private int fat = 0;
      	private int sodium = 0;
      	private int carbohydrate = 0;
      	public Builder(int servingSize, int servings) {
          	this.servingSize = servingSize;
          	this.servings = servings;
        }
      	public Builder calories(int val) {
          	calories = val;
          	return this;
        }
      	public Builder fat(int val) {
          	fat = val;
          	return this;
        }
      	public Builder sodium(int val) {
          	sodium = val;
          	return this;
        }
      	public Builder carbohydrate(int val) {
          	carbohydrate = val;
          	return this;
        }
      	public NutritionFacts build() {
          	return new NutritionFacts(this);
        }
    }
  	private NutritionFacts(Builder builder) {
      	servingSize = builder.servingsSize;
      	servings = builder.servings;
      	calories = builder.calories;
      	fat = builder.fat;
      	sodium = builder.sodium;
      	carbohydrate = builder.carbohydrate;
    }
}
// 实例化
NutritionFacts cocaCola = new NutritionFacts.Builder(240, 8).calories(100).sodium(35)
  													.carbohydrate(27).build();
```

`NutritionFacts` 是不可变的，所有默认参数值都单独放在一个地方。`builder` 的设值方法返回 `builder` 本身，以便把调用链接起来，得到一个流式 API。这样的客户端代码很容易编写，更为重要的是易于阅读。**Builder 模式模拟了具名的可选参数**

**Build 模式也适用于类层次结构**使用平行层次结构的 `builder` 时，各自嵌套在相应的类中。抽象类有抽象的 `builder`，具体类有具体的 `builder`。

```java
// 抽象类 builder
public abstract class Pizza {
		public enum Topping {HAM, MUSHROOM, ONION, PEPPER, SAUSAGE}
		final Set<Topping> toppings;
		abstract static class Builder<T extends Builder<T>> {
				EnumSet<Topping> toppings = EnumSet.noneOf(Topping.class);
				public T addTopping(Topping topping) {
						toppings.add(Objects.requireNonNull(topping));
						return self();
				}
				abstract Pizza build();
				protected abstract T self();
		}
		Pizza(Builder<?> builder) {
				toppings = builder.toppings.clone();
		}
}
```

`Pizza.Builder` 类型是泛型，带有一个递归型参数。它和抽象的 `self`  方法一样，允许在子类中适当地进行方法链接，不需要转换类型。这个针对 Java 缺乏 self 类型的解决方案，被称为模拟的 self 类型

```java
# 具体类 builder
public class NyPizza extends Pizza {
		public enum Size {SMALL, MEDIUM, LARGE}
		private final Size size;
		public static class Builder extends Pizza.Builder<Builder> {
				private final Size size;
				public Builder(Size size) {
						this.size = Objects.requireNonNull(size);
				}
				@Override
				public NyPizza build(this) {
						return new NyPizza(this);
				}
				@Override
				protected Builder self() {
						return this;
				}
		}
		private NyPizza(Builder builder) {
				super(builder);
				size = builder.size;
		}
}
public class Calzone extends Pizza {
		private final boolean sauceInside;
		public static class Builder extends Pizza.Builder<Builder> {
				private boolean sauceInside = false;
				public Builder sauceInside() {
						sauceInside = true;
						return this;
				}
				@Override
				public Calzone build() {
						return new Calzone(this);
				}
				@Override
				protected Builder self() {
						return this;
				}
				private Calzone(Builder builder) {
						super(builder);
						sauceInside = builder.sauceInside;
				}
		}
}
```

每个子类的构建器中的 `build` 方法，都声明返回正确的子类。在该方法中，子类方法声明返回超类中声明的返回类型的子类型，即协变返回类型（covariant return type）。它允许客户端无需转换类型就能使用这些构建器。

这些「层次化构建器」的客户端代码本质上与简单 `NutritionFacts` 构建器一样。

```java
//客户端代码，假设是在枚举常量上静态导入
NyPizza pizza = new NyPizza.Builder(SMALL).addTopping(SAUSAGE).addTopping(ONION).build();
Calzone calzone = new Calzone.Builder().addTopping(HAM).sauceInside().build();
```

与构造器相比，`builder` 优势在于：它可以有多个可变（varargs）参数，因为 `builder` 是利用单独的方法来设置每一个参数。此外，构造器还可以将多次调用某一个方法而传入的参数集中到一个域中，如前面的调用了两次 `addTopping` 方法

`Builder` 模式十分灵活，可以利用单个 `builder` 构建多个对象。`builder` 的参数可以在调用 `build` 方法来创建对象期间进行调整，也可以随着不同的对象而改变。`builder` 的参数可以在调用 `build` 方法来创建对象期间进行调整，也可以随着不同的对象而改变。`builder` 可以自动填充某些域。

`Builder` 模式也有它自身的不足。为了创建对象，必须先创建它的构建器。虽然创建这个构建器的开销在实践中不那么明显，但是在某些十分注重性能的情况下，可能就成问题了。`Builder` 模式比重叠构造器模式更加冗长，因此它**只在有很多参数的时候才使用，比如 4 个或者更多参数。将来可能需要添加参数，如果一开始就使用构造器或者静态工厂，等到类需要多个参数时才添加构造器，就会无法控制，那些过时的构造器或者静态工厂显得十分不协调。因此最好开始就使用构建器**

如果**类的构造器或静态工厂中具有多个参数，设计这种类时，Builder 模式就是一种好的选择**，特别是当大多数参数都是可选或类型相同的时候。与使用重叠构造器相比，使用 Builder 模式的客户端代码将更易于阅读和编写，构建器也比 JavaBeans 更加安全

#### 用私有构造器或枚举类型强化 Singleton 属性

Singleton 是指仅仅被实例化一次的类。Singleton 通常被用来代表一个无状态的对象，如函数，或者哪些本质上唯一的系统组件。实现 Singleton 有两种常见的方法。这两种方法都要保持构造器为私有的，并导出公有的静态成员，以便允许客户端能够访问该类的唯一实例。

###### final 公有静态成员

```java
public class Elvis {
		public  static final Elvis INSTANCE = new Elvis();
		private Elvis() {}
}
```

私有构造器仅被调用一次，用来实例化公有的静态 final 域 `Elvis.INSTANCE`。由于缺少公有的或者受保护的构造器，所以保证了 `Elvis` 的全局唯一性：一旦 Elvis 类被实例化，将只会存在一个 Elvis 实例。享有特权的客户端可以借助 `AccessibleObject.setAccessible` 方法，通过反射机制调用私有构造器。如果需要抵御这种攻击，可以修改构造器，让它在被要求创建第二个实例的时候抛出异常

公共域方法的主要优势在于，API 很清楚地表明了这个类是一个 Singleton ：公有的静态域是 final，所以该域总是包含相同的对象引用；第二个优势在于它更简单

###### 公有静态工厂方法

```java
public class Elvis {
		private static final Elvis INSTANCE = new Elvis();
		private ELvis() {}
		public static Elvis getInstance() {
				return INSTANCE;
		}
}
```

对于静态方法 `Elvis.getInstance` 的所有调用，都会返回同一个对象引用，所以，永远不会创建其他的 Elvis 实例。享有特权的客户端可以借助 `AccessibleObject.setAccessible` 方法，通过反射机制调用私有构造器。如果需要抵御这种攻击，可以修改构造器，让它在被要求创建第二个实例的时候抛出异常

静态工厂方法的优势在于：在不改变其他 API 的前提下，可以改变该类是否应该为 Singleton 的想法。工厂方法返回该类的惟一实例。但是，它很容易被修改。第二个优势在于，如果应用程序需要修改，可以编写一个 泛型 Singleton 工厂；可以通过方法引用作为提供者。除非满足以上任意优势，否则还是优先考虑公有域的方法

为了将利用上述方法实现的 Singleton 类变成可序列化的，仅仅在声明中加上 implements Serializable 是不够的。为了维护并保证 Singleton，必须声明所有实例域都是瞬时（transient）的，并提供一个 `readResolve` 方法。否则，每次反序列化一个序列化的实例时，都会创建一个新的实例

```java
private Object readResolve() {
		reutrn INSTANCE;
}
```

###### 单元素枚举类型

实现 Singleton 的第三种方法是声明一个包含单个元素的枚举类型

```java
public enum Elvis {
		INSTANCE;
		public void leaveTheBuilding(){}
}
```

这种方法在功能上域公有域方法相似，但更加简洁，无偿地提供了序列号机制，绝对防止多次实例化，即使是在面对复杂的序列化或者反射攻击的时候。虽然这种方法还没有广泛采用，但是**单元素的枚举类型经常成为实现 Singleton 的最佳方法**。如果 Singleton 必须扩展一个超类，而不是扩展 Enum 的时候，则不宜使用这个方法

#### 通过私有构造器强化不可实例化的能力

有时候可能需要编写只包含静态方法和静态域的类。但不要滥用这样的类来编写过程化的程序。可以利用这些类，以 `java.lang.Math` 或者 `java.util.Arrays` 的方式，把基本类型的值或者数组类型上的相关方法组织起来。还可以利用这种类把 final 类上的方法组织起来，因为不能把它们放在子类中

这样的工具类不希望被实例化，因为实例化对它没有任何意义。然而，在缺少显示构造器的情况下，编译器会自动提供一个公有的、无参的缺省构造器。对于用户而言，这个构造器和其他构造器没有任何区别。在已发行的 API 中常常可以看到一些被无意识实例化的类。

*企图通过将类声明为抽象类来强制该类不可被实例化是行不通的*。该类可以被子类化，并且该子类也可以被实例化。甚至会误导用户，以为这种类是专门为了继承而设计的。**让这个类包含一个私有构造器，它就不能被实例化**，由于显示的构造器是私有的，所以不能在外部访问它。

```java
public class Utility {
		private UtilityClass() {
			throw new AssertionError();
		}
}
```

`AssertionError` 不是必需的，但是它可以避免不小心在类的内部调用构造器。它保证该类在任何情况下都不会被实例化。副作用会导致该类不能被子类化（所有的构造器都必需显式或隐式地调用超类构造器，在这种情形下，子类就没有可访问的超类构造器可调用了）

#### 优先考虑依赖注入来引用资源

有许多类会依赖一个或多个底层的资源。**静态工具类和 Singleton 类不适合于需要引用底层资源的类**。满足该需求的最简单的模式是，当创建一个新的实例时，就将该资源传到构造器中。依赖注入的对象资源具有不可变性，因此多个客户端可以共享依赖对象。依赖注入同样适用于构造器、静态工厂和构造器

这个程序模式的另一种有用的变体是，将资源工厂传给构造器。工厂是可以被重复调用来创建类型实例的一个对象。这类工厂具体表现为工厂方法模式。在 Java 8 中增加的接口`Supplier<T>`，最适合用于表示工厂。带有 `Supplier<T>` 的方法，通常应该限制输入工厂的类型参数使用有限制的通配符类型，以便客户端能传入一个工厂，来创建指定类型的任意子类型。

不要用 Singleton 和静态工具类来实现以来一个或多个底层资源的类，且该资源的行为会影响到该类的行为；也不要直接用这个类来创建这些资源。而应该将这些资源或者工厂传给构造器，通过它们来创建类。

#### 避免创建不必要的对象

一般来说，最好能重用单个对象，而不是在每次需要的时候就创建一个相同功能的新对象。如果对象是不可变的，它就始终可以被重用

#### 可以重用场景

* `String` 字符串字面量

  ```java
  # 反例
  String s = new String("bikini");
  # 正例
  String s = "bikini"；
  ```

* 对象的创建成本比其他对象高得多。如果重复地需要这类「昂贵的对象」，建议缓存下来重用。如，正则匹配，**虽然 `String.matches` 方法最易于查看一个字符是否正与表达式相匹配，但并不适合在注重性能的情形中重复使用**：它在内部为正则表达式创建了一个 `Pattern` 实例，确只使用了一次，之后就可以进行垃圾回收了。创建 `Pattern` 实例的成本很高，需要将正则表达式编译成一个有限状态机

  ```java
  static boolean isNumberal(String s) {
  	return s.matches("\d");
  }
  ```

  应该显式的将正则表达式编译成一个 `Pattern` 实例（不可变），让它成为类初始化的一部分，并将它缓存起来，每当调用 `isRomanNumberal` 方法的时候就重用同一个实例
  
  ```java
  public class isNumberal() {
  	private static final Pattern ROMAN = Pattern.compile("\d");
  	static boolean isRomanNumeral(String s) {
  		return ROMAN.matcher(s).matches();
  	}
  }
  
  ```
  
* Map 接口的 `keySet` 方法返回该 `Map` 对象的 `Set` 视图，其中包含该 `Map` 中所有的键（Key）。对于一个给定的 Map 对象，实际上每次调用 `keySet` 都返回同样的 `Set` 实例。虽然被返回的 `Set` 实例一般是可改变的，但是所有返回的对象在功能上是等同的：当其中一个返回对象发生变化的时候，所有的其他的返回对象也要发生变化，因为它们是由同一个 `Map` 实例支持的。

* 避免自动装箱，要优先使用基本类型而不是装箱基本类型，要当心无意识的装箱

* 针对某个给定对象的特定适配器，它不需要创建多个适配器实例

由于小对象的构造器只做很少量的显式工作，所以小对象的创建和回收动作是非常廉价的，特别是在现代 JVM 更是如此。通过创建附加的对象，提升程序的清晰、简洁和功能很重要，反之 ，通过维护自己的对象池来避免创建对象并不是一种好的做法，除非池中的对象是非常重量级的。正确使用对象池的典型对象实例就是数据库连接池。但是，一般而言，维护自己的对象池必定会把代码弄得很乱，同时增加内存占用，并且还会损害性能。现代的 JVM 实现具有高度优化的垃圾回收器，其性能很容易就会超过轻量级对象池的性能

#### 消除过期的对象引用

在支持垃圾回收的语言中，内存泄漏是很隐蔽（这类内存泄漏为无意识的对象保持）如果一个对象引用被无意识地保留起来了，那么垃圾回收机制不仅不会处理这个对象，而且也不会处理被这个对象所引用的所有其他对象。即使只有少量几个对象引用被无意识地保留下，也会有许多的对象被排除在垃圾回收机制之外，从而对性能造成潜在影响。清空过期引用的另一个好处是，如果它们以后又被错误的解除引用，程序就会立即抛出 `NullPointerException` 异常，而不是悄悄地错误运行下去。**清空对象引用应该是一种例外，而不是一种规范行为**。消除过期引用最好的方法是让包含该引用的变量结束其生命周期。如果是在最紧凑的作用域内定义每一个变量，这种情形就会自然而然地发生。即**只要类是自己管理内存，程序员就应该警惕内存泄漏问题**一旦元素被释放放掉，则该元素中包含的任何对象引用都应该就清空。

**内存泄漏的另一个常见来源是缓存**。一旦将对象放到缓存中，就很容易被遗忘掉，从而使得它不再有用之后很长一段时间内仍然留在缓存中。对于这个问题，有几种可能的解决方案：只要在缓存之外存在对某个项的键的引用，该项就有意义，那么就可以用 WeakHashMap 代表缓存；当缓存中的项过期之后，它们就会自动被删除。只有当所要的缓存项的生命周期是由该键外部引用而不是由值决定时，`WeakHashMap` 才有用处。

**内存泄漏的第三个常见来源是监听器和其他回调**如果实现了一个 API，客户端在这个 API 中注册回调，却没有显式地取消注册，那么除非采取某些动作，否则它们就会不断地堆积起来。确保回调立即被当作垃圾回收的最佳方法是只保存它们的弱引用。

#### 避免使用终结方法和清除方法

**终结方法（finalizer）通常是不可预测的，也是很危险的，一般情况下是不必要的**在 Java 9 中用清除方法（cleaner）代替了终结方法。**清除方法没有终结方法那么危险，但仍然是不可预测、运行缓慢，一般情况下也是不必要的**。

终结方法和清除方法的缺点：

* 在于不能保证会被及时执行。从一个对象变得不可达开始，到它的终结方法被执行，所花费的这段时间是任意长的。即注重时间的任务不应该由终结方法或者清除方法来完成。

* 使用终结方法的另一个问题是：如果忽略在终结过程中被抛出来的未被捕获的异常，该对象的终结过程也会终止。未被捕获的异常会使对象处于破坏的状态，如果另一个线程企图使用这种被破坏的对象，则可能发生任何不确定的行为，正常情况下，未被捕获的异常将会使线程终止，并打印栈轨迹，但是如果异常发生在终结方法中，则不会如此，甚至连警告都不会打印出来。

  清除方法没有这个问题，因为使用清除方法的一个类库在控制它的线程。**使用终结方法和清除方法有一个非常严重的性能损失**。

终结方法有一个严重的安全问题：它们为终结方法攻击（finalizer attack）打开了类的大门。终结方法攻击背后的思想很简单：如果从构造器或者它的序列化对等体（readObject 和 readResolve 方法）抛出异常，恶意子类的终结方法就可以在构造了一部分的应该已经半途夭折的对象上运行。这个终结方法会将对该对象的引用记录在一个静态域中，阻止它被垃圾回收。一旦记录到异常的对象，就可以轻松地在这个对象上调用任何原本永远不允许在这里出现的方法。**从构造器抛出的异常，应该足以防止对象继续存在；有了终结方法的存在，这一点就做不到了**。这种攻击可能造成致命的后果。`final` 类不会受到终结方法攻击，因为没有人能够编写出 `final` 类的恶意子类。**为了防止非 `final` 类受到终结方法攻击，要编写一个空的 final 的 finalize 方法**

如果类的对象中封装的资源确实需要终止，只需**让类实现AutoCloseable**，并要求其客户端在每个实例不再需要的时候调用 close 方法，一般是利用 `try-with-resources` 确保终止，即使遇到异常也是如此。该实例必须记录下自己是否已经被关闭了：close 方法必须在一个私有域中记录下「该对象已经不再有效」。如果这些方法是在对象已经终止之后被调用，其他的方法就必须检查这个域，并抛出 `IllegalStateException` 异常

清除方法的两种合理用途：

* 当资源的所有者忘记调用它的 close 方法时，终结方法或者清除方法可以充当「安全网」。虽然这样做并不能保证终结方法或清除方法会被及时地运行，但在客户端无法正常结束操作的情况下，迟一点释放资源总比永远不释放要好。
* 本地对等体是一个本地（非 Java 的）对象（native object），普通对象通过本地方法（native method）委托给一个本地对象。因为本地对等体不是一个普通对象，所以垃圾回收器不会知道它，当它的 Java 对等体背回收的时候，它不会被回收。如果本地对等体没有关键资源，并且性能也可以接受的话，那么清除方法或终结方法正是只需这项任务最合适的工具。如果本地对等体拥有必须被及时终止的资源，或者性能无法接受，那么该类就应该具有一个 close 方法

#### try-with-resource 优先于 try-finally

在处理必须关闭的资源时，始终要优先考虑 `try-with-resources`，而不是 `try-finally`。这样代码更加简单、清晰，产生的异常也更有价值。有了 `try-with-resources` 语句，在使用必须关闭的资源时，就能更轻松的编写正确的代码

```java
try (BufferedReader br = new BufferedReader(new FileReader(path))) {
  	return br.readLine();
}
try (InputStream in = new FileInputStream(src);
     OutputStream out = new FileOutputStream(dst)
    ) {
  	byte[] buf = new byte[BUFFER_SIZE];
}
```


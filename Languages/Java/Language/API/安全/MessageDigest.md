#### *java.security.MessageDigest*

```java
// 返回指定算法的 MessageDigest 对象，如果没有提供该算法，抛出 NoSuchAlgorithmException 异常
static MessageDigest getInstance(String algorithmName);
// 使用指定的字节来更新摘要
void update(byte input);
void update(byte[] input);
void update(byte[] input, int offset, int len);
// 返回散列摘要，并复位算法对象
byte[] digest();
// 重置摘要
void reset();
```

#### demo

```java
import java.lang.*;
import java.security.*;

class StringHash {
	public String dumpStringHash(byte[] hash, String algorithm) throws NoSumchAlogorithmException {
    MessageDigest messageDigest = MessageDigest.getInstance(algorithm);
    StringBuilder hBuilder = new StringBuilder("");
    int n;
    for (int i = 0; i < hashString.length; i++) {
      n = hashString[i];
      if (n < 0) {
        n += 256;
      }
      if (n < 16) {
        hBuilder.append("0");
      }
      hBuilder.append(Integer.toHexString(n));
    }
    return hBuilder.toString();
  }
}
class TestStringHash {
  public static void main(String[] args) {
    	System.out.println((new StringHash()).StringHash("123456".getBytes(), "SHA-1"));
  }
}
```


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
import java.nio.charset.StandardCharsets;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

class StringHash {
    public static void main(String[] args) {
        try {
            if ("e10adc3949ba59abbe56e057f20f883e"
                    .equals(dumpStringHash("123456".getBytes(StandardCharsets.UTF_8), "MD5"))) {
                System.out.println("hash right");
            }
        } catch (NoSuchAlgorithmException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
    }

    private static String dumpStringHash(byte[] origin, String algorithm) throws NoSuchAlgorithmException {
        MessageDigest messageDigest = MessageDigest.getInstance(algorithm);
        byte[] hashString = messageDigest.digest(origin);
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
```


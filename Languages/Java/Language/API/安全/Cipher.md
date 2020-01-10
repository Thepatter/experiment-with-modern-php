#### *javax.crypto.Cipher*

```java
// 返回实现了指定加密算法的 Ciper 对象，未提供该算法则抛出 NoSuchAlgorithmException
static Cipher getInstance(String algorithmName);
static Cipher getInstance(String algorithnName, String providerName);
// 返回密码块的大小，如果该密码块不是一个分组密码，则返回 0
int getBlockSize();
// 如果下一个输入数据块拥有给定的字节数，则返回所需的输出缓冲区大小。运行要考虑密码对象中已缓冲的字节数量
int getOutputSize(int inputLength);
// 对加密算法对象进行初始化。Mode 为 ENCRYPT_MODE, DECRYPT_MODE, WARP_MODE, UMWAP_MODE
void init(int mode, Key key);
// 对输入数据块进行转换。返回输出
byte[] update(byte[] in);
byte[] update(byte[] in, int offset, int length);
// 对输入数据块进行转换，返回 out 的字符数
int update(byte[] in, int offset, int length, byte[] out);
// 转换输入的最后一个数据块，并刷新该加密算法对象的缓冲
byte[] doFinal();
byte[] doFinal(byte[] in);
// 转换输入的最后一个数据块，返回输出
byte[] deFinal(byte[] in, int offset, int length);
// 转换输入的最后一个数据块，返回 out的字节数
int doFinal(byte[] in, int offset, int length, byte[] out);
```

#### demo

```java
import java.nio.charset.StandardCharsets;
import javax.crypto.Cipher;
import javax.crypto.spec.*;

class Crypt {
    public static final String key = "0000000000000000";
    public static final String CipherMode = "AES/ECB/PKCS5Padding";
    public static final SecretKeySpec aeskey = new SecretKeySpec(Crypt.key.getBytes(StandardCharsets.UTF_8), "AES");
    public static void main(String[] args)
    {
        String origin = "Hello World";
        String encrypt = Crypt.encrypt(origin);
        System.out.println(encrypt);
        System.out.println(decrypt(encrypt));
    }

    public static String encrypt(String content)
    {
        try {
            Cipher cipher = Cipher.getInstance(Crypt.CipherMode);
            cipher.init(Cipher.ENCRYPT_MODE, new SecretKeySpec(Crypt.key.getBytes(StandardCharsets.UTF_8), "AES"));
            byte[] result = cipher.doFinal(content.getBytes(StandardCharsets.UTF_8));
            // return byte2hex(result);
            return (java.util.Base64.getEncoder()).encodeToString(result);
        } catch(Exception e) {
            e.printStackTrace();
        }
        return null;
    }

    public static String decrypt(String encrypt)
    {
        try {
            Cipher cipher = Cipher.getInstance(Crypt.CipherMode);
            cipher.init(Cipher.DECRYPT_MODE, Crypt.aeskey);
            byte[] encryptBytes = (java.util.Base64.getDecoder()).decode(encrypt);
            byte[] result = cipher.doFinal(encryptBytes);
            return new String(result, StandardCharsets.UTF_8);
        } catch (Exception e) {
            e.printStackTrace();
        }
        return null;
    }

    public static String byte2hex(byte[] b) {
        StringBuffer sb = new StringBuffer(b.length * 2);
        String tmp;
        for (int n = 0; n < b.length; n++) {
            tmp = (Integer.toHexString(b[n] & 0XFF));
            if (tmp.length() == 1) {
                sb.append("0");
            }
            sb.append(tmp);
        }
        return sb.toString();
    }
    
    public static byte[] hex2byte(String inputString) {
        if (inputString == null || inputString.length() < 2) {
            return new byte[0];
        }
        inputString = inputString.toLowerCase();
        int l = inputString.length() / 2;
        byte[] result = new byte[l];
        for (int i = 0; i < l; ++i) {
            String tmp = inputString.substring(2 * i, 2 * i + 2);
            result[i] = (byte) (Integer.parseInt(tmp, 16) & 0xFF);
        }
        return result;
    }
}
```


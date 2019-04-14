## java AES 加密

```java
import com.alibaba.fastjson.JSONObject;
import com.google.common.base.Charsets;
import com.google.common.collect.Maps;
import jodd.http.HttpRequest;
import javax.crypto.Cipher;
import javax.crypto.spec.SecretKeySpec;
import java.util.Map;
// AES 加密，ECB 模式，pkcs5padding 填充（填0）
public class Cryptos {
    private static final String CipherMode = "AES/ECB/PKCS5Padding";
    public static void main(String[] args) {
        try {
            String appKey = "4YGMX71o1X2OExge";//法信提供
            //组装参数数据，json格式
            JSONObject json = new JSONObject();
            json.put("mobile", "18950505050");
            String data = json.toJSONString();
            //加密，合作方
            String encodeData = encrypt(data, appKey);
            System.out.println(encodeData);
            //请求法信接口
            String appId = "97d531ebda2f4aa1ba46ce11a6c71c68";//法信提供
            Map<String, Object> map = Maps.newLinkedHashMap();
            map.put("data", encodeData);
            map.put("appId", appId);
            String pushUrl = "http://xx.xxxxx/api/common/register";//法信提供
            HttpRequest.post(pushUrl).form(map).send();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
    /**
     * aes加密
     *
     * @param content 加密前内容
     * @param key     16位key
     * @return 加密后的数据转16进制大写
     */
    public static String encrypt(String content, String key) {
        byte[] data = null;
        try {
            data = content.getBytes(Charsets.UTF_8);
        } catch (Exception e) {
            e.printStackTrace();
        }
        data = encrypt(data, new SecretKeySpec(key.getBytes(Charsets.UTF_8), "AES").getEncoded());
        String result = byte2hex(data);
        return result;
    }
    /**
     * aes解密
     *
     * @param content 密文字符串
     * @param key     key
     * @return 解密后的字符串
     */
    public static String decrypt(String content, String key) {
        byte[] data = null;
        try {
            data = hex2byte(content);
        } catch (Exception e) {
            e.printStackTrace();
        }
        data = decrypt(data, key.getBytes(Charsets.UTF_8));
        if (data == null)
            return null;
        String result = new String(data, Charsets.UTF_8);
        return result;
    }
    public static byte[] encrypt(byte[] content, byte[] key) {
        try {
            Cipher cipher = Cipher.getInstance(CipherMode);
            cipher.init(Cipher.ENCRYPT_MODE, new SecretKeySpec(key, "AES"));
            byte[] result = cipher.doFinal(content);
            return result;
        } catch (Exception e) {
            e.printStackTrace();
        }
        return null;
    }
    public static byte[] decrypt(byte[] content, byte[] key) {
        try {
            Cipher cipher = Cipher.getInstance(CipherMode);
            cipher.init(Cipher.DECRYPT_MODE, new SecretKeySpec(key, "AES"));
            byte[] result = cipher.doFinal(content);
            return result;
        } catch (Exception e) {
            e.printStackTrace();
        }
        return null;
    }
    private static byte[] hex2byte(String inputString) {
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
        return sb.toString().toUpperCase();
    }
}
```


package aes;

import javax.crypto.Cipher;
import javax.crypto.KeyGenerator;
import javax.crypto.SecretKey;
import java.io.*;
import java.security.GeneralSecurityException;
import java.security.Key;
import java.security.SecureRandom;

/**
 * @author zyw
 */
public class AESTest {
    public static void main(String[] args) throws IOException, GeneralSecurityException, ClassNotFoundException {
        if (args[0].equals("-genkey")) {
            KeyGenerator keyGenerator = KeyGenerator.getInstance("AES");
            SecureRandom random = new SecureRandom();
            keyGenerator.init(random);
            SecretKey key = keyGenerator.generateKey();
            try (ObjectOutputStream out = new ObjectOutputStream(new FileOutputStream(args[1]))) {
                out.writeObject(key);
            }
        } else {
            int mode;
            if (args[0].equals("-encrypt")) {
                mode = Cipher.ENCRYPT_MODE;
            } else {
                mode = Cipher.DECRYPT_MODE;
            }
            try (ObjectInputStream keyIn = new ObjectInputStream(new FileInputStream(args[3])); InputStream in = new FileInputStream(args[1]); OutputStream out = new FileOutputStream(args[2])) {
                Key key = (Key) keyIn.readObject();
                Cipher cipher = Cipher.getInstance("AES");
                cipher.init(mode, key);
                Util.crypt(in, out, cipher);
            }
        }
    }
}

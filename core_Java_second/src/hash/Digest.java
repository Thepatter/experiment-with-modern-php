package hash;

import java.io.*;
import java.nio.file.*;
import java.security.*;
/**
 * @author zyw
 */
public class Digest {
    public static void main(String[] args) throws IOException, GeneralSecurityException {
        String algname = args.length >= 2 ? args[1] : "sha-1";
        MessageDigest alg = MessageDigest.getInstance(algname);
        byte[] input = Files.readAllBytes(Paths.get("core_Java_second/src/hash/input.txt"));
        byte[] hash = alg.digest(input);
        String d = "";
        for (int i = 0; i < hash.length; i++) {
            int v = hash[i] & 0xFF;
            if (v < 16) {
                d += "0";
            }
            d += Integer.toString(v, 16).toUpperCase() + " ";
        }
        System.out.println(d);
        System.out.println(byteArrayToHexStr(hash));
    }

    private static String byteArrayToHexStr(byte[] byteArray) {
        if (byteArray == null) {
            return null;
        }
        char[] hexArray = "0123456789ABCDEF".toCharArray();
        char[] hexChars = new char[byteArray.length * 2];
        for (int j = 0; j < byteArray.length; j++) {
            int v = byteArray[j] & 0xFF;
            hexChars[j * 2] = hexArray[ v >>> 4];
            hexChars[j * 2 + 1] = hexArray[v & 0x0F];
        }
        return new String(hexChars);
    }
}

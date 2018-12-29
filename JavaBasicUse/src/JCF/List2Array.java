package JCF;

import java.util.Arrays;
import java.util.List;

public class List2Array {
    public static void main(String[] args) {
        String[] arrays = new String[]{"hello", "world", "hash"};
        List<String> stringList = Arrays.asList(arrays);
        System.out.println(stringList);
        String[] ss = stringList.stream().toArray(String[]::new);
        String[] sss = stringList.toArray(new String[stringList.size()]);
    }
}

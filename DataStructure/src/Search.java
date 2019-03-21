import java.util.Arrays;

public class Search {
    public static void main(String[] args) {
        int key = 23;
        int[] sourceArray = {2, 24, 32, 11, 23, 123, 56, 132, 134, 123, 159,173, 125, 23, 35, 25, 26, 771};
        Arrays.sort(sourceArray);
        for (int i = 0; i < sourceArray.length; i++) {
            System.out.print(sourceArray[i] + ",");
        }
        int searchKey = binarySearch(key, sourceArray, 0, sourceArray.length - 1);
        System.out.println(searchKey);
    }

    private static int binarySearch(int key, int[] srcArray, int start, int end)
    {
        if (start > end) {
            return -1;
        }
        int mid = start + (end - start) / 2;
        if (key == srcArray[mid]) {
            return mid;
        }
        if (key < srcArray[mid]) {
            return binarySearch(key, srcArray, start, mid -1);
        } else {
            return binarySearch(key, srcArray, mid + 1, end);
        }
    }
}

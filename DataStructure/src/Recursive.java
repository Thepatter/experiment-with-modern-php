import java.util.Arrays;

public class Recursive {

    public static void main(String[] args) {
        int[] searchArray = {1, 2, 3, 5, 7, 11, 23, 24, 25, 35, 38,238, 23,123,491,192};
        Arrays.sort(searchArray);
        for (int i: searchArray
             ) {
            System.out.println(i);
        }
        int recursiveSearch = binarySearchInRecursive(searchArray, 0, searchArray.length - 1, 24);
        int loopSearch = binarySearchInLoop(24, searchArray);
        if (recursiveSearch == loopSearch) {
            System.out.println("相等：" + recursiveSearch);
        } else {
            System.out.println("不相等");
        }
    }

    /** 递归实现二分查找 **/
    private static int binarySearchInRecursive(int[] srcArray, int start, int end, int key) {
        int mid = (end - start) / 2 + start;
        if (srcArray[mid] == key) {
            return mid;
        }
        if (start > end) {
            return -1;
        } else if (key > srcArray[mid]) {
            return binarySearchInRecursive(srcArray, mid + 1, end, key);
        } else if (key < srcArray[mid]) {
            return binarySearchInRecursive(srcArray, start, mid -1, key);
        }
        return -1;
    }

    /** 循环实现二分查找 **/
    private static int binarySearchInLoop(int key, int[] searchArray) {
        int start = 0;
        int end = searchArray.length - 1;
        while (start <= end) {
            int mid = (end - start) / 2 + start;
            if (key < searchArray[mid]) {
                end = mid - 1;
            } else if (key > searchArray[mid]) {
                start = mid + 1;
            } else {
                return mid;
            }
        }
        return -1;
    }
}

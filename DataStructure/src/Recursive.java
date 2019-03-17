public class Recursive {
    public static void main(String[] args) {
        int[] searchArray = {1, 2, 3, 5, 7, 11, 23, 24, 25, 35, 38};
        System.out.println(binarySearch(searchArray, 0, searchArray.length -1, 24));
    }
    public static int binarySearch(int[] srcArray, int start, int end, int key) {
        int mid = (end - start) / 2 + start;
        if (srcArray[mid] == key) {
            return mid;
        }
        if (start >= end) {
            return -1;
        } else if (key > srcArray[mid]) {
            return binarySearch(srcArray, mid + 1, end, key);
        } else if (key < srcArray[mid]) {
            return binarySearch(srcArray, start, mid -1, key);
        }
        return -1;
    }
}

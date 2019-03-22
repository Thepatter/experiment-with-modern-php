package easy;

public class TwoSum {

    public static void main(String[] args) {
        int[] sourceArray = {1, 2, 4, 6, 8, 11, 32, 12, 29};
        int target = 19;
        int[] twoSumIntKey = (new TwoSum()).sum(sourceArray, target);
        for (int i: twoSumIntKey
             ) {
            System.out.println("source key: " + i + " , source value: " + sourceArray[i]);
        }

    }

    public int[] sum(int[] source, int target)
    {
        int[] twoSum = new int[2];
        for (int i = 0; i < source.length; i++) {
            for (int l = 1; l < source.length; l++) {
                if (source[l] != source[i] && source[i] + source[l] == target) {
                    twoSum[0] = i;
                    twoSum[1] = l;
                }
            }
        }
        return twoSum;
    }
}

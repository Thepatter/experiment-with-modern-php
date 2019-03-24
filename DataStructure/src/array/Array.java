package array;

public class Array {
    private int[] data;

    private int currentCount = 0;

    private int count = 10;

    Array() {
        this.data = new int[count];
    }

    Array(int count) {
        this.count = count;
        this.data = new int[count];
    }

    public int get(int index) {
        if (index >= 0 && index < this.data.length - 1) {
            return this.data[index];
        }
        return -1;
    }

    public int add(int value) {
        if (++currentCount > count) {
            return -1;
        }
        data[currentCount - 1] = value;
        return currentCount;
    }

    public boolean del(int index) {
        if (index < 0 && index > count - 1) {
            return false;
        }
        for (int i = index; i < data.length -1; i++) {
            data[i] = data[i + 1];
        }
        --count;
        return true;
    }

    public boolean put(int index, int value) {
        if (index < 0 || index >= count) {
            return false;
        }
        data[index] = value;
        return true;
    }

    public void sort(String order)
    {
        for (int i = 0; i < data.length; i++) {
            for (int l = i + 1; l < data.length; l++) {
                int tmp;
                if ("asc".equals(order)) {
                    if (data[i] > data[l]) {
                        tmp = data[i];
                        data[i] = data[l];
                        data[l] = tmp;
                    }
                } else {
                    if (data[i] < data[l]) {
                        tmp = data[i];
                        data[i] = data[l];
                        data[l] = tmp;
                    }
                }
            }
        }
    }

    public void bubbleSort()
    {
        for (int i = 0; i < data.length; i++) {
            boolean exchange = false;
            for (int j = i + 1; j < data.length; j++) {
                if (data[j] > data[i]) {
                    int tmp = data[i];
                    data[i] = data[j];
                    data[j] = tmp;
                    exchange  = true;
                }
            }
            if (!exchange) {
                break;
            }
        }
    }
    public void print() {
        for (int i = 0; i < count; i++) {
            System.out.print(data[i] + " ");
        }
        System.out.println();
    }

    public static void main(String[] args) {
        Array array = new Array(5);
        System.out.println(array.add(31));
        System.out.println(array.add(21));
        System.out.println(array.add(5112));
        System.out.println(array.add(933));
        System.out.println(array.add(1012));
        array.sort("asc");
        array.print();
        array.sort("desc");
        array.print();
        array.bubbleSort();
        array.print();
    }
}

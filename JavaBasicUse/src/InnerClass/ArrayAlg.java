package InnerClass;

class ArrayAlg {
    public static class Pair
    {
        private double first;
        private double second;

        public Pair(double f, double s)
        {
            first = f;
            second = s;
        }

        public double getFirst()
        {
            return first;
        }

        public double getSecond()
        {
            return second;
        }
    }
    public static Pair minmax(double[] values)
    {
        double min = Double.POSITIVE_INFINITY;
        double max = Double.NEGATIVE_INFINITY;
        for (double v: values)
        {
            if (min > v) {
                min = v;
            }
            if (max < v) {
                max = v;
            }
        }
        return new Pair(min, max);
    }
}

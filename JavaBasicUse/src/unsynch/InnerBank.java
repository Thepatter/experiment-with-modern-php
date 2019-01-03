package unsynch;

import java.util.Arrays;

class InnerBank {
    private final double[] accounts;

    InnerBank(int n, double initialBalance)
    {
        accounts = new double[n];
        Arrays.fill(accounts, initialBalance);
    }

    synchronized void transfer(int from, int to, double amount) throws InterruptedException
    {
        while (accounts[from] < amount) {
            wait();
        }
        System.out.print(Thread.currentThread());
        accounts[from] -= amount;
        System.out.printf(" Total Balance: %10.2f%n", getTotalBalance());
        notifyAll();
    }
    synchronized double getTotalBalance()
    {
        double sum = 0;
        for (double a: accounts) {
            sum += a;
        }
        return sum;
    }
    int size()
    {
        return accounts.length;
    }
}

package unsynch;


import java.util.Arrays;
import java.util.concurrent.locks.Condition;
import java.util.concurrent.locks.Lock;
import java.util.concurrent.locks.ReentrantLock;

class ConditionBank {
    private final double[] accounts;
    private Lock bankLock;
    private Condition sufficientFunds;

    ConditionBank(int n, double initialBalance)
    {
        this.accounts = new double[n];
        Arrays.fill(accounts, initialBalance);
        bankLock = new ReentrantLock();
        sufficientFunds = bankLock.newCondition();
    }

    void transfer(int from, int to, double amount) throws InterruptedException
    {
        bankLock.lock();
        try {
            while (accounts[from] < amount) {
                sufficientFunds.await();
            }
            System.out.print(Thread.currentThread());
            accounts[from] -= amount;
            System.out.printf(" Total Balance: %10.2f%n", getTotalBalance());
            sufficientFunds.signalAll();
        } finally {
            bankLock.unlock();
        }
    }

    private double getTotalBalance()
    {
        bankLock.lock();
        try {
            double sum = 0;
            for (double a: accounts) {
                sum += a;
            }
            return sum;
        } finally {
            bankLock.unlock();
        }
    }

    int size() {
        return accounts.length;
    }
}

package common;

import java.time.LocalDate;

public class Employee implements Comparable<Employee>, Cloneable
{
    private String name;

    private double salary;

    private LocalDate hireDay;

    public Employee(){}

    public Employee(String name)
    {
        this.name = name;
    }

    public Employee(String name, double salary, int year, int month, int day)
    {
        this.name = name;
        this.salary = salary;
        hireDay = LocalDate.of(year, month, day);
    }

    public String getName()
    {
        return name;
    }

    public double getSalary()
    {
        return salary;
    }

    public LocalDate getHireDay()
    {
        return hireDay;
    }

    public void raiseSalary(double byPercent)
    {
        double raise = salary * byPercent / 100;
        salary += raise;
    }

    public int compareTo(Employee other)
    {
        return Double.compare(salary, other.salary);
    }

}
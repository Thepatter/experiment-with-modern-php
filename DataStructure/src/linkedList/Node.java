package linkedList;

public class Node
{
    private int data;

    private Node next;

    Node(){}

    Node(int data, Node next)
    {
        this.data = data;
        this.next = next;
    }

    public void setData(int data) {
        this.data = data;
    }

    public int getData() {
        return data;
    }

    public void setNext(Node next) {
        this.next = next;
    }

    public Node getNext() {
        return next;
    }

}

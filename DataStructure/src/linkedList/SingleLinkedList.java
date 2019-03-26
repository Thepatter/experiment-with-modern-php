package linkedList;

public class SingleLinkedList {

    private int length;

    private Node current;

    private Node header;

    SingleLinkedList(){
    }

    SingleLinkedList(int length)
    {
        this.length = length;
        header = current = new Node();
    }
}

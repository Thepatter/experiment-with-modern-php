package LinkedList;

public interface SingleLinkedList extends LinkedList{

    public boolean add(SingleLinkedListNode singleLinkedListNode);

    public boolean del(SingleLinkedListNode singleLinkedListNode);

    public SingleLinkedListNode getNodeByIndex(int index);

    public int search(SingleLinkedListNode singleLinkedListNode);
}

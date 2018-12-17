package LinkedList;

import java.util.Iterator;

public class SingleLinkedListImp implements SingleLinkedList {

    private SingleLinkedListNode[] singleLinkedListNodes;

    private int singleLinkedListLength;

    private int currentSingleLinkedListPoint = 0;

    private int currentSingleLinkedListLength;

    private static final double expansionCoefficient = 0.75;

    private boolean isScalability;

    SingleLinkedListImp () {
        this(10, false);
    }

    SingleLinkedListImp (int singleLinkedListLength, boolean isScalability) {
        this.singleLinkedListLength = singleLinkedListLength;
        this.singleLinkedListNodes = new SingleLinkedListNode[singleLinkedListLength];
        this.isScalability = isScalability;
    }

    @Override
    public boolean add(SingleLinkedListNode singleLinkedListNode) {
        if (!this.isScalability) {
            if (currentSingleLinkedListLength < singleLinkedListLength) {
                currentSingleLinkedListPoint++;
                currentSingleLinkedListLength++;
                singleLinkedListNodes[currentSingleLinkedListPoint] = singleLinkedListNode;
                return true;
            }
            return false;
        }
        if (currentSingleLinkedListLength >= singleLinkedListLength * expansionCoefficient) {
            SingleLinkedListNode[] newSingleLinkedListNodes = new SingleLinkedListNodeImp[singleLinkedListLength * LinkedList.DilatationMultiplier];
            System.arraycopy(singleLinkedListNodes, 0, newSingleLinkedListNodes, 0, singleLinkedListNodes.length);
            singleLinkedListNodes = newSingleLinkedListNodes;
        }
        currentSingleLinkedListPoint++;
        currentSingleLinkedListLength++;
        singleLinkedListNodes[currentSingleLinkedListPoint] = singleLinkedListNode;
        return true;
    }

    @Override
    public boolean del(SingleLinkedListNode singleLinkedListNode) {
        return false;
    }

    @Override
    public boolean delByIndex(int index) {
//        if (Arrays.binarySearch(singleLinkedListNodes, index)) {
//
//        }
        return false;
    }

    @Override
    public int getLinkedListLength() {
        return 0;
    }

    @Override
    public int search(SingleLinkedListNode singleLinkedListNode) {
        return 0;
    }

    public SingleLinkedListNode getNodeByIndex(int index)
    {
        return this.singleLinkedListNodes[index];
    }

    @Override
    public LinkedListNode[] toArray() {
        return singleLinkedListNodes;
    }

    @Override
    public Iterator iterator() {
        return null;
    }
}

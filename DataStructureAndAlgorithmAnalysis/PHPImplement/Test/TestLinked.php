<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/12/15
 * Time: 14:07
 */

use DataStructureAndAlgorithmAnalysis\PHPImplement\LinkedList\Node;
use DataStructureAndAlgorithmAnalysis\PHPImplement\LinkedList\LinkedList;

require '../LinkedList/LinkedList.php';
require '../LinkedList/Node.php';

$node1 = new Node('this is node first');
$node2 = new Node('this is node second');
$node3 = new Node('this is node three');
$node4 = new Node('this is node four');
$node5 = new Node('this is node five');

$linkedList = new LinkedList(5);
$linkedList->add($node1);
$linkedList->add($node2);
$linkedList->add($node3);
$linkedList->add($node4);
$linkedList->add($node5);
//$linkedList->del($node3);
var_dump($linkedList->toArray());exit;
foreach ($linkedList as $key => $node) {
    echo json_encode([
            'key' => $key,
            'node' => $node->getData,
            'prev' => $node->getPrev,
            'next' => $node->getNext,
        ]) . PHP_EOL;
}
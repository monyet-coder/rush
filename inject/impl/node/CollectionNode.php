<?php
namespace rush\inject\impl\node;

use rush\inject\impl\GraphNode;
use rush\inject\impl\GraphNodeVisitor;

class CollectionNode implements GraphNode {
    private $elements = [];
    
    public function addElement(GraphNode $node) {
        $this->elements[] = $node;
    }
    
    public function getElements() {
        return $this->elements;
    }
    
    public function accept(GraphNodeVisitor $visitor) {
        $visitor->visitCollection($this);
    }
}
<?php
namespace rush\inject\impl\node;

use rush\inject\impl\GraphNode;
use rush\inject\impl\GraphNodeVisitor;

class InstanceNode implements GraphNode {    
    private $index;
    
    public function __construct($index) {
        $this->index = $index;
    }
    
    public function getIndex() {
        return $this->index;
    }
    
    public function accept(GraphNodeVisitor $visitor) {
        $visitor->visitInstance($this);
    }
}
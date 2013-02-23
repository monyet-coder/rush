<?php
namespace rush\inject\impl\node;

use rush\inject\impl\GraphNode;
use rush\inject\impl\GraphNodeVisitor;

class ConstantNode implements GraphNode {
    private $value;
    
    public function __construct($value) {
        $this->value = $value;
    }
    
    public function getValue() {
        return $this->value;
    }
    
    public function accept(GraphNodeVisitor $visitor) {
        $visitor->visitConstant($this);
    }
}
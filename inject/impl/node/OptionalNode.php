<?php
namespace rush\inject\impl\node;

use rush\inject\impl\GraphNode;
use rush\inject\impl\GraphNodeVisitor;
use rush\inject\impl\node\UnresolvedNode;

class OptionalNode implements GraphNode {
    private $delegate, 
            $defaultValue;
    
    public function __construct(UnresolvedNode $delegate, $defaultValue) {
        $this->delegate = $delegate;
        $this->defaultValue = $defaultValue;
    }
    
    public function getDelegate() {
        return $this->delegate;
    }
    
    public function isResolved() {
        return $this->delegate->isResolved();
    }
    
    public function getDefaultValue() {
        return $this->defaultValue;
    }
    
    public function accept(GraphNodeVisitor $visitor) {
        $visitor->visitOptional($this);
    }
}
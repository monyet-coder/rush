<?php
namespace rush\inject\impl\node;

use rush\inject\Key;
use rush\reflect\Parameter;
use rush\inject\impl\GraphNode;
use rush\inject\impl\GraphNodeVisitor;

class UnresolvedNode implements GraphNode {
    private $key,
            $parameter,
            $delegate;
    
    public function __construct(Key $key, Parameter $parameter) {
        $this->key = $key;
        $this->parameter = $parameter;
    }
    
    public function getParameter() {
        return $this->parameter;
    }
    
    public function getKey() {
        return $this->key;
    }
    
    public function getDelegate() {
        return $this->delegate;
    }
    
    public function setDelegate(GraphNode $delegate) {
        $this->delegate = $delegate;
    }
    
    public function isResolved() {
        return (bool)$this->delegate;
    }
    
    public function accept(GraphNodeVisitor $visitor) {
        $visitor->visitUnresolved($this);
    }
}
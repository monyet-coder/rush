<?php
namespace rush\inject\impl\node;

use rush\inject\impl\GraphNode;
use rush\inject\impl\GraphNodeVisitor;

class SingletonNode implements GraphNode {
    private $delegate,
            $index = false;
    
    public function __construct(GraphNode $node) {
        $this->delegate = $node;
    }
    
    public function setIndex($index) {
        $this->index = $index;
    }
    
    public function isResolved() {
        return $this->index !== false;
    }
    
    public function getDelegate() {
        return $this->delegate;
    }
    
    public function getIndex() {
        return $this->index;
    }
    
    public function accept(GraphNodeVisitor $visitor) {
        $visitor->visitSingleton($this);
    }
}
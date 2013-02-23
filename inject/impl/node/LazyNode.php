<?php
namespace rush\inject\impl\node;

use rush\inject\TypeKey;
use rush\inject\impl\GraphNode;
use rush\inject\impl\GraphNodeVisitor;

class LazyNode implements GraphNode {
    private $key;
    
    public function __construct(TypeKey $key) {
        $this->key = $key;
    }
    
    public function getKey() {
        return $this->key;
    }
    
    public function accept(GraphNodeVisitor $visitor) {
        $visitor->visitLazy($this);
    }
}
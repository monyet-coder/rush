<?php
namespace rush\inject\impl\node;

use rush\reflect\Type;
use rush\inject\impl\GraphNode;
use rush\inject\impl\GraphNodeVisitor;

class InlineNode implements GraphNode {
    private $class,
            $params;
    
    public function __construct(Type $class, array $parameters) {
        $this->class = $class->name;
        $this->params = $parameters;
    }
    
    public function getClass() {
        return $this->class;
    }
    
    public function getParameters() {
        return $this->params;
    }
    
    public function accept(GraphNodeVisitor $visitor) {
        $visitor->visitInline($this);
    }
}
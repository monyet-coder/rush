<?php
namespace rush\inject\impl\node;

use rush\inject\impl\GraphNode;
use rush\inject\impl\GraphNodeVisitor;
use rush\reflect\Method;

class ProviderNode implements GraphNode {
    private $class,
            $method,
            $line,
            $params;
    
    public function __construct(Method $method, array $parameters) {
        $this->class = $method->class;
        $this->method = $method->name;
        $this->line = $method->getStartLine();
        $this->params = $parameters;
    }
    
    public function getClass() {
        return $this->class;
    }
    
    public function getMethod() {
        return $this->method;
    }
    
    public function getLine() {
        return $this->line;
    }
    
    public function getParameters() {
        return $this->params;
    }

    public function accept(GraphNodeVisitor $visitor) {
        $visitor->visitProvider($this);
    }
}
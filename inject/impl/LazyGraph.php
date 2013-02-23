<?php
namespace rush\inject\impl;

use rush\inject\Key;

class LazyGraph implements Graph {
    private $builder,
            $delegate,
            $resolverVisitor,
            $builded = false;

    public function __construct(
            GraphBuilder $builder, 
            Graph $delegate,
            ModuleResolverVisitor $resolverVisitor) {
        $this->builder = $builder;
        $this->delegate = $delegate;
        $this->resolverVisitor = $resolverVisitor;
    }
    
    public function addNode(Key $key, GraphNode $node) {
        $this->delegate->addNode($key, $node);
    }

    public function getNode(Key $key) {
        if(!$this->builded) {
            $this->builder->build();
            $this->resolverVisitor->resolve();
            
            $this->builded = true;
        }
        
        return $this->delegate->getNode($key);
    }
    
    public function getIterator() {
        return $this->delegate->getIterator();
    }
}
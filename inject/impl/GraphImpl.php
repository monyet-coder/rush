<?php
namespace rush\inject\impl;

use ArrayIterator;
use rush\inject\Key;
use rush\inject\impl\GraphNode;
use rush\inject\impl\node\ProviderNode;

class GraphImpl implements Graph {
    private $nodes = [];
    
    public function addNode(Key $key, GraphNode $node) {
        $hash = $key->hash();
        if(isset($this->nodes[$hash]) and $this->nodes[$hash] instanceof ProviderNode) {
            throw BindingException::bindingAlreadyConfigured($key, $this->nodes[$hash]);
        }
        
        $this->nodes[$key->hash()] = $node;
    }

    public function getNode(Key $key) {
        if(isset($this->nodes[$key->hash()])) {
            return $this->nodes[$key->hash()];
        }
    }
    
    public function getIterator() {
        return new ArrayIterator($this->nodes);
    }
}
<?php
namespace rush\reflect\impl\matcher;

use rush\reflect\Type;

class Only extends AbstractMatcher {
    public $n;
    
    public function __construct($typeName) {
        $this->n = $typeName;
    }
    
    public function matches(Type $type) {
        return $type->name === $n;
    }
}
<?php
namespace rush\reflect\impl\matcher;

use rush\reflect\Type;

class WithModifier extends AbstractMatcher {
    public $m;
    
    public function __construct($modifier) {
        $this->m = $modifier;
    }
    
    public function matches(Type $type) {
        return $type->getModifiers() & $this->m;
    }
}
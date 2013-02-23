<?php
namespace rush\reflect\impl\matcher;

use rush\reflect\Type;

class SubTypeOf extends AbstractMatcher {
    public $t;
    
    public function __construct($type) {
        $this->t = $type;
    }
    
    public function matches(Type $type) {
        return $type->isSubTypeOf($this->t);
    }
}
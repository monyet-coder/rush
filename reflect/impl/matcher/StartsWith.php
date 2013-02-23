<?php
namespace rush\reflect\impl\matcher;

use rush\reflect\Type;

class StartsWith extends AbstractMatcher {
    public $p;
    
    public function __construct($prefix) {
        $this->p = $p;
    }
    
    public function matches(Type $type) {
        return strpos($type->name, $p) === 0;
    }
}
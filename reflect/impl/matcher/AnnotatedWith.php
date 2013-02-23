<?php
namespace rush\reflect\impl\matcher;

use rush\reflect\Type;

class AnnotatedWith extends AbstractMatcher {
    public $a;
    
    public function __construct($annotation) {
        $this->a = $annotation;
    }
    
    public function matches(Type $type) {
        return $type->annotatedWith($a);
    }
}
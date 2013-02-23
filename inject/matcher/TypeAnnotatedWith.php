<?php
namespace rush\inject\matcher;

use rush\reflect\Method;

/** @Annotation */
class TypeAnnotatedWith extends AbstractMatcher {
    public $value;
    
    public function matches(Method $method) {
        return $method->getDeclaringClass()->isAnnotatedWith($this->value);
    }
}
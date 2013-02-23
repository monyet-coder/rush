<?php
namespace rush\inject\matcher;

use rush\reflect\Method;

/** @Annotation */
class AnnotatedWith extends AbstractMatcher {
    public $value;
    
    public function matches(Method $method) {
        return $method->isAnnotatedWith($this->value);
    }
}
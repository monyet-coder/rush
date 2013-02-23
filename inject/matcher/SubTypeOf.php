<?php
namespace rush\inject\matcher;

use rush\reflect\Method;

/** @Annotation */
class SubTypeOf extends AbstractMatcher {
    public $value;
    
    public function matches(Method $method) {
        return $method->getDeclaringClass()->isSubTypeOf($this->value);
    }
}
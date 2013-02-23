<?php
namespace rush\inject\matcher;

use rush\reflect\Method;

/** @Annotation */
class Type extends AbstractMatcher {
    public $value;
    
    public function matches(Method $method) {
        return $method->class === $this->value;
    }
}
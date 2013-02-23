<?php
namespace rush\inject\matcher;

use rush\reflect\Method;

/** @Annotation */
class StartsWith extends AbstractMatcher {
    public $value;
    
    public function matches(Method $method) {
        return strpos($method->name, $this->value) === 0;
    }
}
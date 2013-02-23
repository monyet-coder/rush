<?php
namespace rush\inject\matcher;

use rush\reflect\Method;

/** @Annotation */
class ParameterAnnotatedWith extends AbstractMatcher {
    public $value;
    
    public function matches(Method $method) {
        return $method->hasParametersAnnotatedWith($this->value);
    }
}
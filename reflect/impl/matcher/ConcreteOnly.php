<?php
namespace rush\reflect\impl\matcher;

use rush\reflect\Type;

class ConcreteOnly extends AbstractMatcher {
    public function matches(Type $type) {
        return !$type->isAbstract();
    }
}
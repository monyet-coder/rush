<?php
namespace rush\reflect\impl\matcher;

use rush\reflect\Type;

class InterfaceOnly extends AbstractMatcher {
    public function matches(Type $type) {
        return $type->isInterface();
    }
}
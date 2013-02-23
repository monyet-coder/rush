<?php
namespace rush\reflect\impl\matcher;

use rush\reflect\Type;

class Any extends AbstractMatcher {
    public function matches(Type $type) {
        return true;
    }
}
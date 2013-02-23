<?php
namespace rush\reflect\impl\matcher;

use rush\reflect\Type;

class EndsWith extends AbstractMatcher {
    public $s, $l;
    
    public function __construc($suffix) {
        $this->s = $suffix;
        $this->l = strlen($suffix);
    }
    
    public function matches(Type $type) {
        return substr_compare($type->name, $this->s, -$this->l, $this->l) === 0;
    }
}
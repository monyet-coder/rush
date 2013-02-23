<?php
namespace rush\reflect\impl\matcher;

use rush\reflect\Matcher;
use rush\reflect\Type;

abstract class AbstractMatcher implements Matcher {
    public function andIt(Matcher $matcher) {
        return new AndMatcher($this, $matcher);
    }
    
    public function orIt(Matcher $matcher) {
        return new OrMatcher($this, $matcher);
    }
    
    public function andNot(Matcher $matcher) {
        return $this->andIt(new NotMatcher($matcher));
    }
    
    public function orNot(Matcher $matcher) {
        return $this->orIt(new NotMatcher($matcher));
    }
}

class AndMatcher extends AbstractMatcher {
    public $f, $s;
    
    public function __construct(Matcher $first, Matcher $second) {
        $this->f = $first;
        $this->s = $second;
    }    
    
    public function matches(Type $type) {
        return $this->f->matches($type) && $this->s->matches($type);
    }
}

class OrMatcher extends AbstractMatcher {
    public $f, $s;
    
    public function __construct(Matcher $first, Matcher $second) {
        $this->f = $first;
        $this->s = $second;
    }
    
    public function matches(Type $type) {
        return $this->f->matches($type) || $this->s->matches($type);
    }
}

class NotMatcher extends AbstractMatcher {
    public $m;
    
    public function __construct(Matcher $matcher) {
        $this->m = $matcher;
    }
    
    public function matches(Type $type) {
        return !$this->m->matches($type);
    }
}
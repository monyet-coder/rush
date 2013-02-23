<?php
namespace rush\inject\impl\aspect;

use rush\reflect\Method;
use rush\inject\Intercept;

class PointCut {
    private $intercepts;
    
    public function __construct(array $intercepts) {
        $this->intercepts = $intercepts;
    }
    
    public function matches(Method $method) {
        foreach($this->intercepts as $intercept) {
            if($this->interceptMatches($intercept, $method)) {
                return true;
            }
        }
        
        return false;
    }
    
    public function interceptMatches(Intercept $intercept, Method $method) {
        foreach($intercept->value as $matcher) {
            if(!$matcher->matches($method)) {
                return false;
            }
        }
        
        return true;
    }
}
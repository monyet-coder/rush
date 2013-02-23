<?php
namespace rush\inject\impl\aspect;

use rush\aspect\intercept\MethodInvocation;
use rush\inject\Injector;
use rush\inject\Key;
use rush\reflect\Method;

class TerminalInvocation implements MethodInvocation {
    public $i, $k, $m, $a;
    
    public function __construct(
            Injector $injector, 
            Key $key, 
            Method $method, 
            array $args) {
        $this->i = $injector;
        $this->k = $key;
        $this->m = $method;
        $this->a = $args;
    }
    
    public function getArguments() {
        return $this->a;
    }

    public function getMethod() {
        return $this->m;
    }

    public function proceed() {
        return $this->m->invokeArgs(
            $this->i->getInstanceByKey($this->k),
            $this->a
        );
    }
}
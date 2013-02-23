<?php
namespace rush\inject\impl\aspect;

use rush\aspect\intercept\MethodInvocation;
use rush\aspect\intercept\MethodInterceptor;

class DelegateInvocation implements MethodInvocation {
    public $d, $i;
    
    public function __construct(
            MethodInvocation $delegate, 
            MethodInterceptor $interceptor) {
        $this->d = $delegate;
        $this->i = $interceptor;
    }
    
    public function getArguments() {
        return $this->d->getArguments();
    }

    public function getMethod() {
        return $this->d->getMethod();
    }

    public function proceed() {
        return $this->i->intercept($this->d);
    }
}
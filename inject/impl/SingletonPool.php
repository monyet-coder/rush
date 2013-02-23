<?php
namespace rush\inject\impl;

use SplFixedArray;

class SingletonPool extends SplFixedArray {
    public function pushInstance($instance) {
        $index = $this->getSize();
        
        $this->setSize($this->getSize() + 1);
        $this[$index] = $instance;
        
        return $index;
    }
}
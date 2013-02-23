<?php
namespace rush\inject\impl;

use rush\inject\TypeKey;
use rush\reflect\Method;
use rush\reflect\Reflector;
use rush\inject\Injector;

class LazyProxyFactory {
    private $storage,
            $reflector,
            $lazyGen;
    
    public function __construct(
            CodeStorage $storage, 
            Reflector $reflector,
            LazyProxyGenerator $lazyGen) {
        $this->storage = $storage;
        $this->reflector = $reflector;
        $this->lazyGen = $lazyGen;
    }
    
    public function getProxyClass(TypeKey $key) {
        $className = 'Lazy__'.$key->hash();
        $fqcn = $this->storage->load($className);
        if(empty($fqcn)) {
            $code = $this->lazyGen->getProxyCodeFor($this->reflector->getType($key->getType()), $className);
            
            $this->storage->store($className, $code);
            
            $fqcn = $this->storage->load($className);
        }
        
        return $fqcn;
    }
}
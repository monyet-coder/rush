<?php
namespace rush\reflect;

class Type extends \ReflectionClass implements Annotated {
    private $reflector, $annotations;
    
    public function __construct($typeName, Reflector $reflector) {
        parent::__construct($typeName);
        
        $this->reflector = $reflector;
        $this->annotations = $reflector->getAnnotations($this);
    }
    
    public function getAnnotations($annotationName = null) {
        if($annotationName === null) {
            return $this->annotations;
        }
        
        $annotations = array();
        foreach($this->annotations as $annotation) {
            if($annotation instanceof $annotationName) {
                $annotations[] = $annotation;
            }                
        }

        return $annotations;
    }
    
    public function getAnnotation($annotationName) {
        foreach($this->annotations as $annotation) {
            if($annotation instanceof $annotationName) {
                return $annotation;
            }
        }
    }
    
    public function isAnnotatedWith($annotation) {
        if(is_object($annotation)) {
            foreach($this->annotations as $annot) {
                if($annot == $annotation) {
                    return $annot;
                }
            }
        }
        
        return (bool)$this->getAnnotation($annotation);
    }
    
    public function isSubTypeOf($typeName) {
        $super = $this->reflector->getType($typeName);
        
        return 
            ($super->isInterface() && $this->implementsInterface($typeName)) 
            ||
            ($this->isSubclassOf($typeName));
    }
    
    public function getMethod($name) {
        return $this->reflector->getMethod($this, $name);
    }
    
    public function getMethods($filter = null) {
        $methods = array();
        foreach($filter ? parent::getMethods($filter) : parent::getMethods() as $method) {
            $methods[] = $this->getMethod($method->name);
        }
        
        return $methods;
    }
    
    public function getConstructor() {
        if (($ctor = parent::getConstructor())) {
            return $this->reflector->getMethod($this, $ctor->name);
        }
    }
}
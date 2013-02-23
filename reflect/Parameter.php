<?php
namespace rush\reflect;

class Parameter extends \ReflectionParameter implements Annotated {
    private $reflector, $annotations;
    
    public function __construct(Method $method, $parameterName, Reflector $reflector) {
        parent::__construct(array($method->class, $method->name), $parameterName);
        
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
}
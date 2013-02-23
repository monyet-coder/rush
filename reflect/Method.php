<?php
namespace rush\reflect;

class Method extends \ReflectionMethod implements Annotated {
    private $reflector, 
            $annotations,
            $type;
    
    public function __construct(Type $type, $methodName, Reflector $reflector) {
        parent::__construct($type->name, $methodName);
        
        $this->type = $type;
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
    
    public function getParameters() {
        $params = array();
        foreach(parent::getParameters() as $param) {
            $params[] = $this->reflector->getParameter($this, $param->name);
        }
        
        return $params;
    }
    
    public function getDeclaringClass() {
        return $this->type;
    }
    
    public function hasParametersAnnotatedWith($annotation) {
        foreach($this->getParameters() as $parameter) {
            if($parameter->isAnnotatedWith($annotation)) {
                return true;
            }
        }
        
        return false;
    }
}
<?php
namespace rush\reflect\impl;

use rush\reflect\Reflector;
use rush\reflect\Matcher;
use rush\reflect\Annotated;
use rush\reflect\Type;
use rush\reflect\Field;
use rush\reflect\Method;
use rush\reflect\Parameter;
use rush\inject\Inject;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use rush\reflect\InvocationHandler;

class ReflectorImpl implements Reflector {
    public $finder, $autoloader, $reader, $parameterReader;
    
    /** @Inject */
    public function __construct(
            TypeFinder $finder,
            Autoloader $autoloader,
            Reader $reader) {
        $this->finder = $finder;
        $this->autoloader = $autoloader;
        $this->reader = $reader;
        $this->parameterReader = new ParameterReader();
    }

    public function find(Matcher $matcher) {
        $this->finder->find($matcher);
    }

    public function autoload($prefix, $path) {
        return $this->autoloader->autoload($prefix, $path);
    }

    public function getAnnotations(Annotated $annotated) {
        if($annotated instanceof \ReflectionClass) {
            return $this->reader->getClassAnnotations($annotated);
        }
        
        if($annotated instanceof \ReflectionMethod) {
            return $this->reader->getMethodAnnotations($annotated);
        }
        
        if($annotated instanceof \ReflectionProperty) {
            return $this->reader->getPropertyAnnotations($annotated);
        }
        
        if($annotated instanceof \ReflectionParameter) {
            return $this->parameterReader->getParameterAnnotations($annotated);
        }
    }

    public function getField(Type $type, $fieldName) {
        return new Field($type, $fieldName, $this);
    }

    public function getMethod(Type $type, $methodName) {
        return new Method($type, $methodName, $this);
    }

    public function getType($typeName) {
        return new Type($typeName, $this);
    }
    
    public function getParameter(Method $method, $parameterName) {
        return new Parameter($method, $parameterName, $this);
    }
    
    static public function create() {
        $fwPath = realpath(__DIR__.'/../../../');

        $autoloader = new Autoloader();
        $autoloader->autoload('rush', $fwPath);
        
        $scanner = new TypeScanner();
        $finder = new TypeFinder($scanner);
        
        $reader = new AnnotationReader();
        AnnotationRegistry::registerLoader(new LoaderImpl('rush', $fwPath, true));        
        
        return new self($finder, $autoloader, $reader);
    }
}
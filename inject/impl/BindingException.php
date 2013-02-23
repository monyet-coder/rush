<?php
namespace rush\inject\impl;

use LogicException;
use rush\inject\Key;
use rush\reflect\Type;
use rush\reflect\Method;
use rush\reflect\Parameter;
use rush\inject\ConstantKey;
use rush\inject\impl\node\ProviderNode;

class BindingException extends LogicException {
    static public function unstaticProvider(Method $method) {
        return new self('Provider method '.$method->class.'::'.$method->name.'() must be static.');
    }
    
    static public function uninstantiableType(Type $class) {
        return new self('Type '.$class->name.' is uninstantiable, maybe you forget to configure it ?');
    }
    
    static public function unbindableParameter(Parameter $parameter) {
        return new self('Parameter $'.$parameter->name.' in '.$parameter->getDeclaringClass()->name.'::'.$parameter->getDeclaringFunction()->name.'() is unbindable, maybe you forgot to configure it ?');
    }
    
    static public function unresolvedDependency(Key $key) {
        return new self('Unable to resolve dependency of '.$key.', maybe you forgot to configure it ?');
    }
    
    static public function bindingAlreadyConfigured(Key $key, ProviderNode $configuredNode, Method $method) {
        return new self('Binding for '.$key.' in module '.$method->class.' line '.$method->getStartLine().' is already configured by module '.$configuredNode->getClass().' in line '.$configuredNode->getLine());
    }
}
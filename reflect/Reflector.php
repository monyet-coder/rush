<?php
namespace rush\reflect;

interface Reflector {
    function find(Matcher $matcher);
    
    function autoload($prefix, $path);
    
    function getAnnotations(Annotated $annotated);
    
    function getType($typeName);
    
    function getField(Type $type, $fieldName);
    
    function getMethod(Type $type, $methodName);
    
    function getParameter(Method $method, $parameterName);
}
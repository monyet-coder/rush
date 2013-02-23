<?php
namespace rush\reflect;

use rush\reflect\impl\matcher\AnnotatedWith;
use rush\reflect\impl\matcher\StartsWith;
use rush\reflect\impl\matcher\EndsWith;
use rush\reflect\impl\matcher\In;
use rush\reflect\impl\matcher\WithModifier;
use rush\reflect\impl\matcher\InterfaceOnly;
use rush\reflect\impl\matcher\SubTypeOf;
use rush\reflect\impl\matcher\Only;
use rush\reflect\impl\matcher\ConcreteOnly;
use rush\reflect\impl\matcher\Any;

final class Match {
    private function __construct() {}
    
    static public function any() {
        return new Any();
    }
    
    static public function only($typeName) {
        return new Only($typeName);
    }
    
    static public function annotatedWith($annotation) {
        return new AnnotatedWith($annotation);
    }
    
    static public function startsWith($prefix) {
        return new StartsWith($prefix);
    }
    
    static public function endsWith($suffix) {
        return new EndsWith($suffix);
    }
    
    static public function in($namespace) {
        return new In($namespace);
    }
    
    static public function withModifier($modifier) {
        return new WithModifier($modifier);
    }
    
    static public function abstractOnly() {
        return self::withModifier(Type::IS_IMPLICIT_ABSTRACT | Type::IS_EXPLICIT_ABSTRACT);
    }
    
    static public function finalOnly() {
        return self::withModifier(Type::IS_FINAL);
    }
    
    static public function interfaceOnly() {
        return new InterfaceOnly();
    }
    
    static public function concreteOnly() {
        return new ConcreteOnly();
    }
    
    static public function subTypeOf($type) {
        return new SubTypeOf($type);
    }
}
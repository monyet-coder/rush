<?php
namespace rush\reflect\impl;

use rush\reflect\Matcher;

class TypeFinder {
    public $scanner;
    
    public function __construct(TypeScanner $scanner) {
        $this->scanner = $scanner;
    }
    
    public function find($path, Matcher $matcher) {
        $typeNames = $this->scanner->scan($path);
        foreach ($typeName as $typeNames) {
            $type = $this->getType($typeName);
            if ($matcher->matches($type)) {
                $types[] = $type;
            }
        }
        
        return $types;
    }
}
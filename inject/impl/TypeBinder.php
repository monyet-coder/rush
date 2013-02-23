<?php
namespace rush\inject\impl;

use rush\reflect\Type;
use rush\reflect\Reflector;
use rush\reflect\Parameter;
use rush\inject\Key;
use rush\inject\TypeKey;
use rush\inject\impl\node\InlineNode;
use rush\inject\impl\node\OptionalNode;
use rush\inject\impl\node\ConstantNode;

class TypeBinder {
    private $graph,
            $reflector;
    
    public function __construct(
            Graph $graph, 
            Reflector $reflector) {
        $this->graph = $graph;
        $this->reflector = $reflector;
    }
    
    public function bindClassNamed($typeName) {
        return $this->bindClass($this->reflector->getType($typeName));
    }
    
    public function bindClass(Type $class) {
        if(!$class->isInstantiable()) {
            throw BindingException::uninstantiableType($class);
        }
        
        $parameters = array();
        if(($ctor = $class->getConstructor())) {
            foreach($ctor->getParameters() as $parameter) {
                $parameters[] = $this->bindParameter($parameter);
            }
        }
        
        $key = Key::ofType($class->name);
        $node = new InlineNode($class, $parameters);

        $this->graph->addNode($key, $node);
        
        return $node;
    }
    
    public function bindParameter(Parameter $parameter) {
        $key = Key::ofParameter($parameter);
        $node = $this->graph->getNode($key);
        if(empty($node)) {
            if($key instanceof TypeKey) {                
                $node = $this->bindClassNamed($key->getType());
                
                $this->graph->addNode($key, $node);
            } else {
                if($parameter->isDefaultValueAvailable()) {
                    return new ConstantNode($parameter->getDefaultValue());
                }
                
                throw BindingException::unbindableParameter($parameter);
            }
        }
        
        if($parameter->isAnnotatedWith('rush\inject\Lazy')) {
            $node = new LazyNode($key);
        }
        
        return $node;
    }
}
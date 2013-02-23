<?php
namespace rush\inject\impl;

use rush\inject\Key;
use rush\reflect\Type;
use rush\reflect\Method;
use rush\reflect\Parameter;
use rush\reflect\Reflector;
use rush\inject\Provides;
use rush\inject\impl\node\UnresolvedNode;
use rush\inject\impl\node\ProviderNode;
use rush\inject\impl\node\CollectionNode;
use rush\inject\ElementKey;
use rush\inject\impl\aspect\PointCuts;
use rush\inject\impl\node\LazyNode;
use rush\inject\impl\node\OptionalNode;

class ModuleBinder {
    private $graph,
            $reflector;
    
    public function __construct(
            Graph $graph,
            Reflector $reflector) {
        $this->graph = $graph;
        $this->reflector = $reflector;
    }
    
    public function bindModuleNamed($moduleName) {
        return $this->bindModule($this->reflector->getType($moduleName));
    }
    
    public function bindModule(Type $module) {
        foreach($module->getMethods() as $method) {
            if($method->isAnnotatedWith('rush\inject\Provides')) {
                $this->bindProvider($method);
            }
        }
    }
    
    public function bindProvider(Method $method) {
        if(!$method->isStatic()) {
            throw BindingException::unstaticProvider($method);
        }
        
        $type = $method->getAnnotation('rush\inject\Provides')->value;
        $qualifier = $method->getAnnotation('rush\inject\Qualifier');
        if($type === Provides::CONSTANT) {
            $key = Key::ofConstant($qualifier);
        } else if($type === Provides::ELEMENT) {
            $key = Key::ofElement($qualifier);
        } else {
            $key = Key::ofType($type, $qualifier);
        }
        
        $parameters = array();
        foreach($method->getParameters() as $parameter) {
            $parameters[] = $this->bindParameter($parameter);
        }
        
        $node = new ProviderNode($method, $parameters);
        if($key instanceof ElementKey) {
            $collectionNode = $this->graph->getNode($key);
            if( empty($collectionNode) 
                or 
                !$collectionNode instanceof CollectionNode) {
                $collectionNode = new CollectionNode();
                
                $this->graph->addNode($key, $collectionNode);
            }
            
            $collectionNode->addElement($node);
        } else { 
            if(($configuredNode = $this->graph->getNode($key)) 
                and 
                $configuredNode instanceof ProviderNode) {                
                throw BindingException::bindingAlreadyConfigured($key, $configuredNode, $method);
            }
            
            $this->graph->addNode($key, $node);
        }
    }
    
    public function validateParameter(Parameter $parameter) {
        if(($class = $parameter->getClass())) {
            
        }
    }
    
    public function bindParameter(Parameter $parameter) {
        $this->validateParameter($parameter);
        
        $key = Key::ofParameter($parameter);
        $node = $this->graph->getNode($key);
        if(empty($node)) {
            $node = new UnresolvedNode($key, $parameter);
            
            $this->graph->addNode($key, $node);
            
            if($parameter->isDefaultValueAvailable()) {
                $node = new OptionalNode($node, $parameter->getDefaultValue());
            }
        }
        
        if($parameter->isAnnotatedWith('rush\inject\Lazy')) {
            $node = new LazyNode($key);
        }
        
        return $node;
    }
}
<?php
namespace rush\inject\impl;

use rush\inject\Key;
use rush\inject\TypeKey;

class FactoryFactory {
    private $graph,
            $binder,
            $codeStorage;
    
    public function __construct(
            Graph $graph, 
            TypeBinder $binder, 
            CodeStorage $codeStorage) {
        $this->graph = $graph;
        $this->binder = $binder;
        $this->codeStorage = $codeStorage;
    }
    
    public function getFactoryOf(Key $key) {
        $factoryName = 'Factory__'.$key->hash();
        
        $fqcn = $this->codeStorage->load($factoryName);
        if(empty($fqcn)) {
            $node = $this->graph->getNode($key);
            if(empty($node)) {
                if ($key instanceof TypeKey) {
                    $node = $this->binder->bindClassNamed($key->getType());
                    
                    $this->graph->addNode($key, $node);
                } else {
                    throw BindingException::unresolvedDependency($key);
                }
            }
            
            $phpCompiler = new PhpCompilerVisitor($factoryName);
            $node->accept($phpCompiler);
            
            $this->codeStorage->store($factoryName, $phpCompiler->getCode());
            
            $fqcn = $this->codeStorage->load($factoryName);
        }
        
        return $fqcn;
    }
}
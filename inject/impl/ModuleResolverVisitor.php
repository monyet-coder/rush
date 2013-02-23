<?php
namespace rush\inject\impl;

use rush\inject\TypeKey;
use rush\inject\impl\node\CollectionNode;
use rush\inject\impl\node\InlineNode;
use rush\inject\impl\node\ProviderNode;
use rush\inject\impl\node\UnresolvedNode;
use rush\inject\impl\node\SingletonNode;
use rush\inject\impl\node\InstanceNode;
use rush\inject\impl\node\InterceptedNode;
use rush\inject\impl\node\LazyNode;
use rush\inject\impl\node\OptionalNode;
use rush\inject\impl\node\ConstantNode;

class ModuleResolverVisitor implements GraphNodeVisitor {
    private $graph,
            $binder,
            $singletons,
            $singletonCount = 0;
    
    public function __construct(
            Graph $graph, 
            TypeBinder $binder,
            SingletonPool $singletons) {
        $this->graph = $graph;
        $this->binder = $binder;
        $this->singletons = $singletons;
    }
    
    public function resolve() {
        foreach($this->graph as $node) {
            $node->accept($this);
        }
        
        $this->singletons->setSize($this->singletonCount);
    }
    
    public function getSingletonCount() {
        return $this->singletonCount;
    }
    
    public function visitCollection(CollectionNode $node) {
        foreach($node->getElements() as $element) {
            $element->accept($this);
        }
    }

    public function visitInline(InlineNode $node) {
        foreach($node->getParameters() as $parameter) {
            $parameter->accept($this);
        }
    }

    public function visitProvider(ProviderNode $node) {
        foreach($node->getParameters() as $parameter) {
            $parameter->accept($this);
        }
    }

    public function visitSingleton(SingletonNode $node) {
        if($node->isResolved()) return;
        
        $node->getDelegate()->visit($this);

        $node->setIndex($this->singletonCount++);
    }

    public function visitUnresolved(UnresolvedNode $node) {
        if($node->isResolved()) return;
        
        $key = $node->getKey();
        $resolvedNode = $this->graph->getNode($key);
        if($resolvedNode instanceof UnresolvedNode) {
            if($key instanceof TypeKey) {
                $resolvedNode = $this->binder->bindClassNamed($key->getType());
                
                $this->graph->addNode($key, $resolvedNode);
            } else {
                throw BindingException::unbindableParameter($node->getParameter());
            }
        }

        $node->setDelegate($resolvedNode);
    }

    public function visitInstance(InstanceNode $node) {}
    
    public function visitLazy(LazyNode $node) {}
    
    public function visitOptional(OptionalNode $node) {}
    
    public function visitConstant(ConstantNode $node) {}
}
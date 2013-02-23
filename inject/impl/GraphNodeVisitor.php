<?php
namespace rush\inject\impl;

use rush\inject\impl\node\ProviderNode;
use rush\inject\impl\node\SingletonNode;
use rush\inject\impl\node\InlineNode;
use rush\inject\impl\node\UnresolvedNode;
use rush\inject\impl\node\CollectionNode;
use rush\inject\impl\node\InstanceNode;
use rush\inject\impl\node\LazyNode;
use rush\inject\impl\node\OptionalNode;
use rush\inject\impl\node\ConstantNode;

interface GraphNodeVisitor {
    function visitProvider(ProviderNode $node);
    
    function visitSingleton(SingletonNode $node);
    
    function visitInline(InlineNode $node);
    
    function visitUnresolved(UnresolvedNode $node);
    
    function visitCollection(CollectionNode $node);
    
    function visitInstance(InstanceNode $node);
    
    function visitLazy(LazyNode $node);
    
    function visitOptional(OptionalNode $node);
    
    function visitConstant(ConstantNode $node);
}
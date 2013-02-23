<?php
namespace rush\inject\impl;

use rush\inject\impl\node\CollectionNode;
use rush\inject\impl\node\InlineNode;
use rush\inject\impl\node\ProviderNode;
use rush\inject\impl\node\SingletonNode;
use rush\inject\impl\node\UnresolvedNode;
use rush\inject\impl\node\InstanceNode;
use rush\inject\impl\node\InterceptedNode;
use rush\inject\impl\node\LazyNode;
use rush\inject\impl\node\OptionalNode;
use rush\inject\impl\node\ConstantNode;

class PhpCompilerVisitor implements GraphNodeVisitor {
    private $class,
            $compiler;
    
    public function __construct($class) {
        $this->class = $class;
        $this->compiler = new Compiler();
    }
    
    public function getCode() {
        return 'use rush\inject\impl\SingletonPool;
use rush\inject\Injector;
use rush\inject\Key;

class '.$this->class.' {
    static function create(SingletonPool $s, Injector $i) {
        return '.$this->compiler.';
    }
}';
    }
    
    public function writeParameters(array $parameters) {        
        $this->compiler->write('(');
        if(!empty($parameters)) {
            array_shift($parameters)->accept($this);
            foreach($parameters as $parameter) {
                $this->compiler->write(', ');
                $parameter->accept($this);
            }
        }
        $this->compiler->write(')');
    }
    
    public function visitInline(InlineNode $node) {
        $this->compiler->write('new \\');
        $this->compiler->write($node->getClass());
        $this->writeParameters($node->getParameters());
    }

    public function visitProvider(ProviderNode $node) {
        $this->compiler->write('\\');
        $this->compiler->write($node->getClass());
        $this->compiler->write('::');
        $this->compiler->write($node->getMethod());
        $this->writeParameters($node->getParameters());
    }

    public function visitSingleton(SingletonNode $node) {
        $this->compiler->write('$s[');
        $this->compiler->write($node->getIndex());
        $this->compiler->write('] ?: $s[');
        $this->compiler->write($node->getIndex());
        $this->compiler->write('] = ');
        
        $node->getDelegate()->accept($this);
    }

    public function visitUnresolved(UnresolvedNode $node) {
        $node->getDelegate()->accept($this);
    }
    
    public function visitCollection(CollectionNode $node) {
        $this->compiler->write('[');
        foreach($node->getElements() as $element) {
            $element->accept($this);
            $this->compiler->write(', ');
        }
        $this->compiler->write(']');
    }
    
    public function visitInstance(InstanceNode $node) {
        $this->compiler->write('$s[');
        $this->compiler->write($node->getIndex());
        $this->compiler->write(']');
    }

    public function visitIntercepted(InterceptedNode $node) {
        
    }
    
    public function visitLazy(LazyNode $node) {
        $key = $node->getKey();
        
        $this->compiler->write('$i->getLazy(Key::ofType(');
        $this->compiler->write("'");
        $this->compiler->write($key->getType());
        $this->compiler->write("'");
        $this->compiler->write(', ');
        if(($qualifier = $key->getQualifier())) {
            $this->compiler->write('unserialize(');
            $this->compiler->write("'");
            $this->compiler->write(serialize($qualifier));
            $this->compiler->write("'");
            $this->compiler->write(')');
        } else {
            $this->compiler->write('null');
        }
        
        $this->compiler->write('))');
    }
    
    public function visitOptional(OptionalNode $node) {
        if($node->isResolved()) {
            $node->getDelegate()->accept($this);
        } else {
            $this->compiler->writeScalar($node->getDefaultValue());
        }
    }
    
    public function visitConstant(ConstantNode $node) {
        $this->compiler->writeScalar($node->getValue());
    }
}
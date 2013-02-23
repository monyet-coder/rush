<?php
namespace rush\inject\impl;

use rush\reflect\Type;
use rush\reflect\Method;
use rush\reflect\Parameter;

class LazyProxyGenerator {
    private $compiler;
    
    public function getProxyCodeFor(Type $type, $className) {
        $this->compiler = new Compiler();
        
        $this->compiler->writeln('use rush\inject\Key;');
        $this->compiler->writeln('use rush\inject\Injector;');
        
        $this->compiler->nl();
        $this->compiler->write('class ');
        $this->compiler->write($className);
        
        $type->isInterface()
            ? $this->compiler->write(' implements ')
            : $this->compiler->write(' extends ');
        
        $this->compiler->write('\\');
        $this->compiler->write($type->name);
        $this->compiler->writeln(' {');
        
        $this->compiler->writeln('    public $i, $k, $d;

    function __construct(Injector $i, Key $k) {
        $this->i = $i;
        $this->k = $k;
    }
    
    function __d() {
        return $this->d ?: $this->d = $this->i->getInstanceByKey($this->k);
    }
');
        $this->compiler->indent();
        $this->compiler->nl();
        
        foreach($type->getMethods() as $method) {
            if($method->isConstructor()) continue;
            
            $this->writeMethod($method);
        }
        
        $this->compiler->outdent();
        $this->compiler->nl();
        $this->compiler->write('}');
        
        return (string)$this->compiler;
    }
    
    public function writeMethod(Method $method) {
        $this->compiler->write('function ');
        $this->compiler->write($method->name);
        $this->compiler->write('(');
        $this->writeParameters($method->getParameters());
        $this->compiler->write(') {');
        $this->compiler->indent();
        $this->compiler->nl();
        
        $this->compiler->write('return $this->__d()->');
        $this->compiler->write($method->name);
        $this->compiler->write('(');
        $parameters = $method->getParameters();
        if(!empty($parameters)) {
            $this->compiler->write('$');
            $this->compiler->write(array_shift($parameters)->name);
            foreach($parameters as $parameter) {
                $this->compiler->write(', $');
                $this->compiler->write($parameter->name);
            }
        }
        
        $this->compiler->write(');');
        
        $this->compiler->outdent();
        $this->compiler->nl();
        $this->compiler->write('}');
    }
    
    public function writeParameters(array $parameters) {
        if(empty($parameters)) return;
        
        $this->writeParameter(array_shift($parameters));
        foreach($parameters as $parameter) {
            $this->compiler->write(', ');
            $this->writeParameter($parameter);
        }
    }
    
    public function writeParameter(Parameter $parameter) {
        if(($class = $parameter->getClass())) {
            $this->compiler->write('\\');
            $this->compiler->write($class->name);
            $this->compiler->write(' ');
        } else if($parameter->isArray()) {
            $this->compiler->write('array ');
        }
        
        $this->compiler->write('$');
        $this->compiler->write($parameter->name);
    }
}
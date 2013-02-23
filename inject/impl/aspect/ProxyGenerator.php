<?php
namespace rush\inject\impl\aspect;

use rush\inject\impl\Compiler;
use rush\reflect\Type;
use rush\reflect\Method;
use rush\reflect\Parameter;
use rush\inject\impl\node\InterceptorNode;

class ProxyGenerator {
    private $compiler;
    
    public function generateFor(Type $type, $className, array $methodInterceptions) {
        $this->compiler = new Compiler();
        $this->compiler->write('use rush\inject\Key;
use rush\inject\Injector;
use rush\reflect\Reflector;

class '.$className);
        $this->compiler->writeln('use rush\inject\Key;');
        $this->compiler->writeln('use rush\inject\Injector;');
        $this->compiler->writeln('use rush\reflect\Reflector;');
        $this->nl();
        
        $this->compiler->write('class ');
        $this->compiler->write($className);
        
        $type->isInterface() 
            ? $this->compiler->write(' implements ')
            : $this->compiler->write(' extends ');
        
        $this->compiler->write('\\');
        $this->compiler->write($type->name);
        $this->compiler->write(' {');
        $this->compiler->indent();
        $this->compiler->nl();
        
        foreach($type->getMethods() as $method) {
            if(isset($methodInterceptions[$method->name])) {
                $this->generateMethod($method, $methodInterceptions[$method->name]);
            } else {
                $this->generatePlainMethod($method);
            }
        }
        
        $this->compiler->outdent();
        $this->compiler->nl();
        $this->compiler->write('}');
        
        return (string)$this->compiler;
    }
    
    public function generateMethod(Method $method) {
        $this->compiler->write('function (');
        $this->generateParameters($method->getParameters());
        $this->compiler->write(') {');
        $this->compiler->indent();
        $this->compiler->nl();
        
        $this->compiler->outdent();
        $this->compiler->nl();
        $this->compiler->write('}');
    }
    
    public function generatePlainMethod(Method $method, InterceptorNode $node) {
        
    }
    
    public function generateParameters(array $parameters) {
        if(empty($parameters)) return;
        
        $this->generateParameter(array_shift($parameters));
        foreach($parameters as $parameter) {
            $this->compiler->write(', ');
            $this->generateParameter($parameter);
        }
    }
    
    public function generateParameter(Parameter $parameter) {
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